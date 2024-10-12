<div class="grid gap-4 md:grid-cols-12">

    <x-filament::section @class(['md:col-span-4'])>
       <img src="{{ $this->profile }}" alt="{{ $this->record->nama_lengkap }}" role="img" class="object-cover w-full rounded-md"/>
       <div class="flex flex-col items-center justify-center gap-2 mt-4">
         <p class="text-lg font-semibold">{{ $this->record->nama_lengkap }}</p>
            <div class="flex flex-col items-center justify-center text-center">
                <p class="font-semibold  text-primary">{{ $this->pengurus }}</p>
                <p class="font-semibold  text-primary">{{Illuminate\Support\Str::title(str_replace("_", " ", $this->record->kepengurusan_type->type))}} MWC {{ $this->record->mwcnu->nama_kecamatan }}</p>
            </div>
       </div>
    </x-filament::section>

    <div class="space-y-4 md:col-span-8">
        <x-filament::section @class(['']) >
        <x-slot name="heading">
            Detail Profil
        </x-slot>
        <div class="grid gap-2">
            <div class="grid grid-cols-2">
                <span class="">Nama Lengkap</span>
                <span class="font-medium ">{{ $this->record->nama_lengkap }}</span>
            </div>
            <div class="grid grid-cols-2">
                <span class="">NIK</span>
                <span class="font-medium ">{{ $this->record->nik ?? "-" }}</span>
            </div>
            <div class="grid grid-cols-2">
                <span class="">No. Telp / Whatsapp</span>
                <span class="font-medium ">{{ $this->record->telp ?? "-" }}</span>
            </div>
            <div class="grid grid-cols-2">
                <span class="">Email</span>
                <span class="font-medium ">{{ $this->record->email ?? "-" }}</span>
            </div>
            <div class="grid grid-cols-2">
                <span class="">Alamat</span>
                <span class="font-medium ">{{ $this->record->detail_jemaah->alamat_detail ?? "-" }}</span>
            </div>
        </div>
     </x-filament::section>
     <x-filament::section @class([''])>
        <x-slot name="heading">
            Histori Kepengurusan
        </x-slot>
        <div>
            <div class="grid gap-2">
                @if($this->record->history_pengurus->count() > 0)
                    @foreach ($this->record->history_pengurus as $item)
                        <div class="flex items-center">
                                <div class="flex items-center gap-2">
                                    <span class="relative flex items-center justify-center w-3 h-3">
                                        <span @class(["animate-ping absolute inline-flex h-full w-full rounded-full opacity-75",
                                        'bg-green-400' => $item["masa_khidmat"]["end_khidmat"] > Carbon\Carbon::now(),
                                        'bg-red-400' => $item["masa_khidmat"]["end_khidmat"] < Carbon\Carbon::now()])></span>
                                        <span @class(["relative inline-flex rounded-full h-3 w-3", 
                                        'bg-green-500' => $item["masa_khidmat"]["end_khidmat"] > Carbon\Carbon::now(),
                                        'bg-red-500' => $item["masa_khidmat"]["end_khidmat"] < Carbon\Carbon::now(),])></span>
                                    </span>
                                </div>
                            <span class="ml-2 font-medium text-slate-400">{{ Carbon\Carbon::parse($item["masa_khidmat"]["start_khidmat"])->format("d M Y") }} - {{ Carbon\Carbon::parse($item["masa_khidmat"]["end_khidmat"])->format("d M Y") }}</span>
                            <x-filament::link :href="Storage::url($item->surat_keputusan_mwcnu->file_surat)" target="_blank" class="ml-2" size="sm" tag="a"> 
                                {{Illuminate\Support\Str::title(str_replace("_", " ", $item->jabatan))}} {{Illuminate\Support\Str::title($item->posisi)}}, {{Illuminate\Support\Str::title(str_replace("_", " ", $item->type))}}  {{Illuminate\Support\Str::title(str_replace("_", " ", $item->nama_desa))}} {{Illuminate\Support\Str::title(str_replace("_", " ", $item->nama_kecamatan))}}
                            </x-filament::link>
                            {{-- {{dd()}} --}}
                        </div>
                    @endforeach
                @else
                    <div class="flex items-center">
                        <span class="ml-2 font-medium text-slate-400">histori tidak ditemukan</span>
                    </div>
                @endif
                
            </div>
        </div>
     </x-filament::section>
    </div>

</div>