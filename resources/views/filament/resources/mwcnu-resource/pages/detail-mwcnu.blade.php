<x-filament-panels::page>

    <x-filament::tabs class="w-full" label="Content tabs">
        @foreach ($tabs as $item)
            @if ($item["label"] === 'Surat Keputusan')
                @if (auth()->user()->id === $this->record->admin_id || auth()->user()->hasRole(['super_admin',
                'admin_kabupaten']))
                {{-- Jika user adalah admin, tab Surat Keputusan di-disable --}}
                <x-filament::tabs.item class="group" icon="{{ $item['icon'] }}"
                    alpine-active="$wire.get('state') == '{{ Illuminate\Support\Str::slug($item['label']) }}'" :href="route($item['view'], ['record' => $record])"
                    tag="a" disabled>
                    {{ $item['label'] }}
                </x-filament::tabs.item>
                @endif
            @else
            {{-- Tab selain Surat Keputusan ditampilkan normal --}}
            <x-filament::tabs.item class="group" icon="{{ $item['icon'] }}"
                alpine-active="$wire.get('state') == '{{ Illuminate\Support\Str::slug($item['label']) }}'" :href="route($item['view'], ['record' => $record])"
                tag="a">
                {{ $item['label'] }}
            </x-filament::tabs.item>
            @endif
        @endforeach
    </x-filament::tabs>

    <div>
        @if ($state === 'detail')
            @livewire(App\Livewire\DetailLengkapMwcnu::class, ['record' => $this->record])
        @elseif($state === 'surat-keputusan' && (auth()->user()->id === $this->record->admin_id ||
        auth()->user()->hasRole(['super_admin',
        'admin_kabupaten'])))
            @livewire(App\Filament\Resources\MwcnuResource\Pages\SuratKeputusanMwcnu::class, ['record' => $this->record])
        @elseif($state === 'warga')
            @livewire(App\Filament\Resources\MwcnuResource\Pages\JamaahMwcnu::class, ['record' => $this->record])
        @elseif($state === 'kepengurusan')
            @livewire(App\Filament\Resources\MwcnuResource\Pages\PengurusMwcnu::class, ['record' => $this->record])
        @endif
    </div>

</x-filament-panels::page>