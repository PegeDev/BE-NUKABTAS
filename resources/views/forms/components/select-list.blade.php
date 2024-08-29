<?php
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;
use Illuminate\Support\Str;

$options = collect($getOptions())->map(function ($item) {
    $decode = json_decode($item['alamat_lengkap'], true);

    $province = Province::where('code', $decode['provinsi'])->first() ?? '';
    $city = $province->cities->where('code', $decode['kota'])->first() ?? '';
    $district = $city->districts->where('code', $decode['kecamatan'])->first() ?? '';

    $profile = $item['profile_picture'];

    if ($item['jenis_kelamin'] === 'laki-laki') {
        $profile = '/avatar_male.png';
    } else {
        $profile = '/avatar_female.png';
    }

    return [
        'id' => $item['id'],
        'nama_lengkap' => $item['nama_lengkap'],
        'profile_picture' => $profile,
        'alamat_lengkap' => [
            'provinsi' => Str::title($province->name),
            'kota' => Str::title(preg_replace('/KABUPATEN/', 'Kab. ', $city->name)),
            'kecamatan' => Str::title($district->name),
        ],
    ];
});

?>

<x-dynamic-component {{ $attributes }} :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{
        state: $wire.$entangle('{{ $getStatePath() }}'),
        search: '',
        filteredOptions: @js($options),
        selectedOptions: [],
        filterOptions() {
            this.filteredOptions = @js($options).filter(option => {
                return option.nama_lengkap.toLowerCase().includes(this.search.toLowerCase());
            });
        },
        selectOption(option) {
            if (!this.selectedOptions.some(selected => selected.id === option.id)) {
                this.selectedOptions.push(option);
            }
            this.filteredOptions = @js($options).filter(option => {
                return !this.selectedOptions.some(selected => selected.id === option.id);
            });
            this.state = this.selectedOptions;
            this.search = ''; // Clear search after selection
        },
        removeOption(id) {
            this.selectedOptions = this.selectedOptions.filter(option => option.id !== id);
        },
        closeDropdown() {
            this.filteredOptions = @js($options);
        }
    }">
        <x-filament::dropdown width="xl" placement="bottom-start" dismissable="true">
            <x-slot name="trigger">
                <x-filament::input.wrapper class="w-full ">
                    <x-filament::input x-model="search" x-on:input.debounce="filterOptions" placeholder="Search..." />
                </x-filament::input.wrapper>
                <template x-if="filteredOptions.length === 0 && search.trim() !== ''">

                </template>
            </x-slot>

            <x-filament::dropdown.list>
                <template x-for="option in filteredOptions" :key="option.id">
                    <x-filament::dropdown.list.item x-on:click="selectOption(option)">
                        <div class="flex items-center gap-2">
                            <img class="w-10 h-10 border-2 border-gray-500 rounded-full" :src="option.profile_picture"
                                alt="Profile Picture">
                            <div class="flex flex-col gap-1">
                                <p x-text="option.nama_lengkap" class="text-primary"></p>
                                <div class="flex gap-0.5">
                                    <p x-text="option.alamat_lengkap.kecamatan + ', ' + option.alamat_lengkap.kota + ', ' + option.alamat_lengkap.provinsi"
                                        class="text-xs"></p>
                                </div>
                            </div>
                        </div>
                    </x-filament::dropdown.list.item>
                </template>
                <template x-if="filteredOptions.length === 0">
                    <x-filament::dropdown.list.item>
                        <p class="text-sm text-gray-500">Jemaah tidak ditemukan</p>
                    </x-filament::dropdown.list.item>
                </template>
            </x-filament::dropdown.list>
        </x-filament::dropdown>

        <!-- Display Selected Options -->
        <template x-if="selectedOptions.length > 0">
            <div class="mt-4 overflow-hidden border border-gray-200 divide-y divide-gray-200 rounded-lg">
                <template x-for="option in selectedOptions" :key="option.id">
                    <div class="flex items-center gap-2 py-1.5 px-4     ">
                        <img class="w-10 h-10 border-2 border-gray-500 rounded-full" :src="option.profile_picture"
                            alt="Profile Picture">
                        <div class="flex flex-col gap-1">
                            <p x-text="option.nama_lengkap" class="text-primary"></p>
                            <div class="flex gap-0.5">
                                <p x-text="option.alamat_lengkap.kecamatan + ', ' + option.alamat_lengkap.kota + ', ' + option.alamat_lengkap.provinsi"
                                    class="text-xs"></p>
                            </div>
                        </div>
                        <x-filament::icon-button icon="heroicon-o-trash" x-on:click="removeOption(option.id)"
                            color="danger" class="ml-auto" label="Delete Selection" />

                    </div>
                </template>
            </div>
        </template>
        <template x-if="selectedOptions.length === 0">
            <div class="flex flex-col items-center gap-2 py-4 mt-4 bg-gray-100 border border-gray-200 rounded-lg h-36">
                <x-filament::icon icon="heroicon-o-user-group" class="text-gray-500" />
                <p class="text-sm text-gray-500">Belum ada pengurus yang dipilih</p>
            </div>

        </template>
    </div>
</x-dynamic-component>
