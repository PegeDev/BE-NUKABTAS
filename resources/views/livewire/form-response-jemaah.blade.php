<?php

use Illuminate\Support\Str;

?>


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
                <x-filament::badge color="primary" icon="heroicon-o-map-pin" icon-position="before" size="sm">
                    {{ Str::Limit($mwcnu->detail_mwcnus->alamat, 30, '...') }}
                </x-filament::badge>

            </div>
        </div>

    </header>


    <form wire:submit="create">
        {{ $this->form }}
    </form>
</div>
