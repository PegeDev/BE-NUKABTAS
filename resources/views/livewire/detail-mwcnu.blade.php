<div class="grid grid-cols-12 gap-6 overflow-y-visible">
    <x-filament::section class="col-span-12 md:col-span-8">
        <x-slot name="heading">
            Informasi Lengkap MWCNU
        </x-slot>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div class="relative">
                <label for="nama_kecamatan" class="font-medium group-hover:text-primary">Nama Kecamatan</label>
                <input id="nama_kecamatan" type="text" value="{{ $record->nama_kecamatan }}"
                    class=" transition-all ease-in-out mt-2 mb-2 block h-11 w-full appearance-none rounded-md border-[1 5px] bg-[#F4F7FF] shadow-sm read-only:cursor-default focus:ring hover:border-primary hover:bg-primary hover:bg-opacity-5 hover:ring-primary hover:ring-opacity-20 border-slate-300 focus:border-primary focus:bg-primary focus:bg-opacity-5 focus:ring-primary focus:ring-opacity-20"
                    readonly>
            </div>
            <div class="relative">
                <label for="ketua_mwcnu" class="font-medium group-hover:text-primary">Ketua Pengurus</label>
                <input id="ketua_mwcnu" type="text" value="Belum Tersedia"
                    class=" transition-all ease-in-out mt-2 mb-2 block h-11 w-full appearance-none rounded-md border-[1 5px] bg-[#F4F7FF] shadow-sm read-only:cursor-default focus:ring hover:border-red-500 hover:bg-primary hover:bg-opacity-5 hover:ring-red-500 hover:ring-opacity-20 border-slate-300 focus:border-red-500 focus:bg-primary focus:bg-opacity-5 text-red-500 focus:ring-red-500 focus:ring-opacity-20"
                    readonly>
            </div>
        </div>

        {{-- <div class="grid grid-cols-2 gap-6">
            @foreach ($this->record->pengurus as $item)
                <div class="flex items-center gap-1.5">
                    <div class="flex flex-col gap-1.5">
                        <p class="text-sm font-medium text-primary">{{ $item->nama_lengkap }}</p>
                    </div>
                </div>
            @endforeach
        </div> --}}

    </x-filament::section>
    <div class="col-span-12 md:col-span-4">

        <div x-data="{
            formResponse: @js($this->record->form_mwcnu),
        }">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <p>Link Pendaftaran</p>
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
                        <a href="{{ route('form-response', ['code' => $this->record->form_mwcnu->code]) }}"
                            target="_blank" class="text-sm text-blue-600 truncate hover:underline">
                            {{ url('/') . '/form/' . $this->record->form_mwcnu->code }}
                        </a>
                        <div class="relative w-fit hover:cursor-pointer">
                            <button @click="copyToClipboard(link)"
                                class="inline-flex items-center justify-center rounded-md bg-blue-100 px-2.5 py-1.5">
                                <p class="text-xs font-medium leading-none text-center text-blue-800">COPY LINK</p>
                            </button>
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
                {{-- <template x-if="{{ $this->record->form_mwcnu === null }}">
                    
                </template> --}}
            </x-filament::section>
        </div>
        {{-- <div class="mb-6 bg-white border rounded-md">
            <div class="flex items-center gap-4 px-6 py-3 border-b">
                <div class="flex items-center gap-3">
                    <p class="text-lg font-semibold leading-normal text-secondary">Link Pendaftaran</p>
                </div><label class="relative inline-flex items-center cursor-pointer"
                    title="Buka/Tutup link pendaftaran"><input type="checkbox" class="sr-only peer">
                    <div
                        class="peer relative h-6 w-11 rounded-full bg-gray-300 after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-primary peer-checked:after:translate-x-full peer-focus:outline-none peer-disabled:cursor-not-allowed peer-disabled:opacity-60">
                    </div>
                </label>
            </div>
            <div class="px-6 py-4">
                <div class="inline-flex items-center gap-3"><a target="_blank"
                        class="leading-normal text-blue-600 hover:underline" href="https://siskader.nu.id/r/8o5ire9r0v">
                        <p>https://siskader.nu.id/r/8o5ire9r0v</p>
                    </a>
                    <div class="relative w-fit hover:cursor-pointer"><button
                            class="inline-flex items-center justify-center rounded-md bg-blue-100 px-2.5 py-1.5">
                            <p class="text-xs font-medium leading-none text-center text-blue-800">COPY LINK</p>
                        </button></div>
                </div>
            </div>
        </div> --}}
    </div>
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
