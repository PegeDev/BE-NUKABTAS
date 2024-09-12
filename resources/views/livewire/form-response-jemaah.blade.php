<div class="max-w-3xl p-1 mx-auto md:p-4">
    <header
        class="inline-flex flex-col items-center justify-end w-full mb-4 space-y-4 overflow-hidden bg-white border border-gray-200 rounded-xl pb-7">
        <img src="/thumbnail.jpg" alt="Banner Formulir">
        <div class="flex flex-col items-center justify-center">
            <div class="flex flex-col gap-y-4">
                <p class="text-xl font-medium text-center">Formulir Pendaftaran</p>
                <p class="mt-0 text-2xl font-bold leading-none text-center uppercase text-primary">MWCNU
                    {{ $mwcnu->nama_kecamatan }}
                </p>
            </div>
            <div class="flex gap-2 mt-4">
                <x-filament::link tag="a" href="{{$mwcnu->detail_mwcnus->google_maps}}" target="_blank">
                    <x-filament::badge icon="heroicon-m-map-pin">
                        {{ Illuminate\Support\Str::Limit($mwcnu->detail_mwcnus->alamat, 36, '...') }}
                    </x-filament::badge>
                </x-filament::link>
            </div>

        </div>

    </header>
    @if ($this->isFormOpen !== true )
    <x-filament::section>
        <div class="flex flex-col items-center justify-center">
            <x-filament::icon icon="heroicon-o-document-text" class="w-32 h-32 text-primary " />
            <div class="w-3/4">
                <h4 class="text-2xl font-bold text-center text-primary">Pendaftaran ditutup</h4>
                <p class="text-center ">Mohon maaf pendaftaran MWCNU {{ $mwcnu->nama_kecamatan }}
                    ditutup, silakan hubungi Admin MWCNU {{$mwcnu->nama_kecamatan}} untuk informasi lebih lanjut.</p>
            </div>
        </div>
    </x-filament::section>
    @elseif($this->isSubmited)
    <x-filament::section>
        <div class="flex flex-col items-center justify-center">
            <x-filament::icon icon="heroicon-o-document-check" class="w-32 h-32 text-primary " />
            <div class="w-3/4">
                <h4 class="text-2xl font-bold text-center text-primary">Berhasil Mendaftar</h4>
                <p class="text-center ">Terimakasih telah mendaftar di MWCNU {{ $mwcnu->nama_kecamatan }}.
                    jika ada pertanyaan, silakan hubungi Admin MWCNU {{$mwcnu->nama_kecamatan}} untuk informasi lebih
                    lanjut.</p>
            </div>
        </div>
    </x-filament::section>
    @else
    <form wire:submit="create">
        {{ $this->form }}
    </form>
    @endif

    <x-filament-actions::modals />
</div>