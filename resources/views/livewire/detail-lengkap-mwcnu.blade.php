<?php
use Illuminate\Support\Carbon;

?>

<div @class([ 'relative grid grid-cols-12 gap-6'=> $record->detail_mwcnus,
    ])>

    {{-- Informasi Lengkap MWCNU --}}

    @if (!$this->record->detail_mwcnus)
    <x-filament::section>
        <div class="inset-0 flex flex-col items-center justify-center ">
            @if ($record->admin_id === auth()->user()->id)
            <x-filament::icon icon="heroicon-o-document-text" class="w-32 h-32 text-primary " />
            <p class="font-medium text-center text-red-500">
                Detail Lengkap Kecamatan {{ $record->nama_kecamatan }}
            </p>
            <p class="text-xs">Silahkan lengkapi detail kecamatan, atau klik pada tombol dibawah!</p>
            <x-filament::button class="mt-4"
                href="{{ route('filament.dashboard.resources.data-kecamatan.edit', ['record' => $this->record]) }}"
                tag="a" icon="heroicon-o-pencil-square" size="sm">Lengkapi
                Detail</x-filament::button>
            @else
            <x-filament::icon icon="heroicon-o-document-text" class="w-32 h-32 text-primary " />
            <p class="font-bold text-center text-red-500 text-lg">
                Detail Kecamatan {{ $record->nama_kecamatan }} Belum Tersedia
            </p>
            <p class="text-xs">Silahkan lengkapi detail kecamatan, atau klik pada tombol dibawah!</p>
            @endif
            </p>
        </div>
    </x-filament::section>
    @else
    <div class="col-span-12 space-y-6 md:col-span-8">
        <x-filament::section class="col-span-12 md:col-span-8">
            <x-slot name="heading">
                Informasi Lengkap <span class="font-bold">MWC {{ $record->nama_kecamatan }}</span>
            </x-slot>

            <div class="grid gap-6 mb-6 md:grid-cols-2">

                <div class="relative space-y-2">
                    <label for="nama_kecamatan" class="text-sm font-medium">Nama Kecamatan</label>
                    <x-filament::input.wrapper>
                        <x-filament::input type="text" value="{{ $record->nama_kecamatan ?? 'tidak tersedia' }}"
                            readOnly />
                    </x-filament::input.wrapper>
                </div>
                <div class="relative space-y-2">
                    <label for="nama_ketua" class="text-sm font-medium">Nama Ketua</label>
                    <x-filament::input.wrapper>
                        <x-filament::input type="text"
                            value="{{ $record->detail_mwcnus->nama_ketua ?? 'tidak tersedia' }}" readOnly />
                    </x-filament::input.wrapper>
                </div>
                <div class="relative space-y-2">
                    <label for="telp_ketua" class="text-sm font-medium">No. Telp / Whatsapp Ketua</label>
                    <x-filament::input.wrapper>
                        <x-filament::input type="text"
                            value="{{ $record->detail_mwcnus->telp_ketua ?? 'tidak tersedia' }}" readOnly />
                    </x-filament::input.wrapper>
                </div>
                <div class="relative space-y-2">
                    <label for="google_maps" class="text-sm font-medium">Google Maps</label>
                    <x-filament::input.wrapper>
                        <x-filament::input type="text"
                            value="{{ $record->detail_mwcnus->google_maps ?? 'tidak tersedia' }}" readOnly />
                    </x-filament::input.wrapper>
                </div>

            </div>
            <div class="relative space-y-2">
                <label for="nama_kecamatan" class="text-sm font-medium">Alamat</label>
                <x-filament::input.wrapper>
                    <textarea type="text" auto
                        class="block h-full w-full border-none bg-transparent px-3 py-1.5 text-base text-gray-950 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 resize-none"
                        readOnly rows="5">{{ $record->detail_mwcnus->alamat ?? 'tidak tersedia' }}</textarea>
                </x-filament::input.wrapper>
            </div>

        </x-filament::section>
        <x-filament::section class="relative col-span-12 md:col-span-8">
            <x-slot name="heading">
                Data Admin <span class="font-bold">MWC {{ $record->nama_kecamatan ?? 'tidak tersedia' }}</span>
            </x-slot>


            <div class="grid gap-6 mb-6 md:grid-cols-2">

                <div class="relative space-y-2">
                    <label for="nama_kecamatan" class="text-sm font-medium">Nama Admin</label>
                    <x-filament::input.wrapper>
                        <x-filament::input type="text"
                            value="{{ $record->detail_mwcnus->nama_admin ?? 'tidak tersedia' }}" readOnly />
                    </x-filament::input.wrapper>
                </div>
                <div class="relative space-y-2">
                    <label for="nama_ketua" class="text-sm font-medium">No. Telp / Whatsapp Admin</label>
                    <x-filament::input.wrapper>
                        <x-filament::input type="text"
                            value="{{ $record->detail_mwcnus->telp_admin ?? 'tidak tersedia' }}" readOnly />
                    </x-filament::input.wrapper>
                </div>

            </div>
            <div class="relative space-y-2">
                <label for="alamat_admin" class="text-sm font-medium">Alamat</label>
                <x-filament::input.wrapper>
                    <textarea type="text" id="alamat_admin"
                        class="block h-full w-full border-none bg-transparent px-3 py-1.5 text-base text-gray-950 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 resize-none"
                        readOnly rows="5">{{ $record->detail_mwcnus->alamat_admin ?? 'tidak tersedia' }}</textarea>
                </x-filament::input.wrapper>
            </div>
            <div class="relative space-y-2">
                <label for="alamat" class="text-sm font-medium">Surat Tugas</label>
                <div
                    style="box-shadow:rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0.05) 0px 1px 2px 0px;padding-top:12px;padding-bottom:12px;padding-left:12px;padding-right:12px;background-color:rgb(244, 247, 255);border-color:rgb(203, 213, 225);border-width:1px;border-radius:6px;gap:8px;align-items:center;width: 100%;height:44px;display:flex;box-sizing:border-box;border:1px solid rgb(203, 213, 225);">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 384 512" height="1em"
                        width="1em" xmlns="http://www.w3.org/2000/svg"
                        style="color:rgb(220, 38, 38);width: 1.25rem;height:20px;display:block;vertical-align:middle;box-sizing:border-box;border:0px solid rgb(229, 231, 235);">
                        <path
                            d="M369.9 97.9L286 14C277 5 264.8-.1 252.1-.1H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V131.9c0-12.7-5.1-25-14.1-34zM332.1 128H256V51.9l76.1 76.1zM48 464V48h160v104c0 13.3 10.7 24 24 24h104v288H48zm250.2-143.7c-12.2-12-47-8.7-64.4-6.5-17.2-10.5-28.7-25-36.8-46.3 3.9-16.1 10.1-40.6 5.4-56-4.2-26.2-37.8-23.6-42.6-5.9-4.4 16.1-.4 38.5 7 67.1-10 23.9-24.9 56-35.4 74.4-20 10.3-47 26.2-51 46.2-3.3 15.8 26 55.2 76.1-31.2 22.4-7.4 46.8-16.5 68.4-20.1 18.9 10.2 41 17 55.8 17 25.5 0 28-28.2 17.5-38.7zm-198.1 77.8c5.1-13.7 24.5-29.5 30.4-35-19 30.3-30.4 35.7-30.4 35zm81.6-190.6c7.4 0 6.7 32.1 1.8 40.8-4.4-13.9-4.3-40.8-1.8-40.8zm-24.4 136.6c9.7-16.9 18-37 24.7-54.7 8.3 15.1 18.9 27.2 30.1 35.5-20.8 4.3-38.9 13.1-54.8 19.2zm131.6-5s-5 6-37.3-7.8c35.1-2.6 40.9 5.4 37.3 7.8z"
                            style="box-sizing:border-box;border:0px solid rgb(229, 231, 235);"></path>
                    </svg>
                    <a href="{{ route('surat-tugas', ['filename' => str_replace('surat_tugas/', '', $record->detail_mwcnus->surat_tugas ?? '')]) }}"
                        target="_blank" rel="noreferrer" class="truncate"
                        style="color:rgb(59, 130, 246);text-decoration:none solid rgb(59, 130, 246);box-sizing:border-box;border:0px solid rgb(229, 231, 235);">{{
                        str_replace('surat_tugas/', '', $record->detail_mwcnus->surat_tugas ?? '') }}</a>
                </div>
            </div>


        </x-filament::section>
    </div>


    <div class="col-span-12 space-y-6 md:col-span-4">

        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-x-4">
                    <p>Status</p>
                    @if ($record->current_status)
                    <x-filament::badge color="{{$record->current_status?->status === App\Enums\MwcnuStatus::DISETUJUI ? 'success'
                        : 'warning' }}">{{$record->current_status->status}}</x-filament::badge>
                    @endif
                </div>
            </x-slot>

            <div class="grid space-y-4">
                <div class="flex flex-col gap-2">

                    @foreach ($record->status as $item)

                    <x-filament::section
                        icon="{{$item->status === App\Enums\MwcnuStatus::DISETUJUI ? 'heroicon-s-check-circle' : 'heroicon-s-information-circle'}}"
                        icon-size="lg"
                        icon-color="{{$item->status === App\Enums\MwcnuStatus::DISETUJUI ? 'success' : 'warning'}}"
                        collapsible collapsed
                        @style(['--c-100:var(--success-100);--c-400:var(--success-400);--c-600:var(--success-600)'=>
                        $item->status ===
                        App\Enums\MwcnuStatus::DISETUJUI,
                        '--c-100:var(--warning-100);--c-400:var(--warning-400);--c-600:var(--warning-600);' =>
                        $item->status === App\Enums\MwcnuStatus::DITINJAU])
                        @class(["fi-color-custom"])
                        >
                        <x-slot name="heading" class="text-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col">
                                    <p class="text-sm text-gray-500 capitalize text-medium">Tanggal {{$item->status ===
                                        App\Enums\MwcnuStatus::DITINJAU ?
                                        "Ditinjau":"Disetujui" }}</p>
                                    <p class="text-sm">
                                        {{ Carbon::parse($item->created_at)->isoFormat('DD MMM Y - HH:mm') }}</p>
                                </div>

                            </div>
                        </x-slot>
                        <div class="flex flex-col text-xs gap-y-2">
                            <span class="text-xs text-gray-500 capitalize text-medium">Pesan:</span>
                            <span class="text-left text-black text-wrap">{{ $item->message ?? '-' }}</span>
                        </div>
                    </x-filament::section>

                    @endforeach

                    @if(count($record->status) == 0)
                    <div>
                        <p>Status tidak ditemukan</p>
                    </div>
                    @endif
                </div>
                @if ($record->current_status && $record->current_status->status !== App\Enums\MwcnuStatus::DISETUJUI)
                <x-filament::button size="sm" icon="heroicon-o-document-check" class="ml-auto uppercase"
                    x-on:click="$dispatch('open-modal', { id: 'form-status' })">
                    setujui
                </x-filament::button>
                @endif
            </div>
        </x-filament::section>



        <div x-data="{
                formResponse: @js($this->record->form_mwcnu),
            }">
            @if($record->admin_id === auth()->user()->id)
            <x-filament::section class="relative overflow-hidden">

                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <p>Formulir Pendaftaran</p>
                        @if ($this->record->form_mwcnu !== null)
                        <label wire:loading.class="hidden" wire:click="handleStatusForm(!formResponse.is_enabled)"
                            for="status" class="relative inline-flex items-center cursor-pointer"
                            title="Buka/Tutup link pendaftaran">
                            <input id="status" type="checkbox" wire:model="isOpen" class="sr-only peer"
                                wire:loading.attr="disabled">
                            <div
                                class="peer relative h-6 w-11 rounded-full bg-gray-300 after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-primary peer-checked:after:translate-x-full peer-focus:outline-none peer-disabled:cursor-not-allowed peer-disabled:opacity-60">
                            </div>
                        </label>
                        <x-filament::loading-indicator class="w-5 h-5" wire:loading />
                        @endif
                    </div>
                </x-slot>
                @if ($this->record->form_mwcnu !== null)
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('form-response', ['code' => $this->record->form_mwcnu->code]) }}" target="_blank"
                        class="text-sm text-blue-600 truncate hover:underline">
                        {{ url('/') . '/form/' . $this->record->form_mwcnu->code }}
                    </a>
                    <div class="relative z-10 w-fit hover:cursor-pointer">
                        <x-filament::button icon="heroicon-m-clipboard-document-check" size="xs" x-on:click="window.navigator.clipboard.writeText('{{ url('/') . '/form/' . $this->record->form_mwcnu->code }}');
                $tooltip('{{__('Berhasil di copy')}}', { timeout: 1500 });" color="info">
                            COPY LINK
                        </x-filament::button>
                    </div>

                </div>
                @else
                <div class="flex items-center gap-4">
                    <p class="text-sm text-gray-500">Belum ada link pendaftaran</p>
                    <x-filament::button wire:click="buatForm()" size="sm">
                        Buat
                    </x-filament::button>
                </div>
                @endif

            </x-filament::section>
            @endif

        </div>

    </div>
    <x-filament::modal id="form-status">
        <x-slot name="heading">
            Setujui Status Kecamatan
        </x-slot>
        <div class="grid gap-4">
            <div class="flex flex-col gap-2">
                <label for="" class="text-sm">Pesan <em>(Optional)</em></label>
                <x-filament::input.wrapper>
                    <textarea wire:model='msg' auto placeholder="Masukkan pesan jika ada"
                        class="block h-full w-full border-none bg-transparent px-3 py-1.5 text-base text-gray-950 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 resize-none"
                        rows="5"></textarea>
                </x-filament::input.wrapper>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <x-filament::button x-on:click="$dispatch('close-modal', { id: 'form-status' })" color="gray"
                    class="uppercase">
                    batal
                </x-filament::button>
                <x-filament::button wire:click="approveStatus" color="primary" class="uppercase">
                    Setujui
                </x-filament::button>
            </div>
        </div>
    </x-filament::modal>

    @endif



</div>
<script>
    function copyToClipboard(text) {
        var tempInput = document.createElement("input");
        tempInput.value = text;
        document.body.appendChild(tempInput);

        tempInput.select();
        document.execCommand("copy");

        document.body.removeChild(tempInput);

        alert("Teks berhasil dicopy: " + text);
    }
</script>