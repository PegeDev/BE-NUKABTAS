<div class="relative flex rounded-md">
    <div class="flex items-center justify-between w-full px-2">
        <div class="flex items-center">
            <x-filament::avatar src="{{ $image }}" alt="{{ $name }}" role="img" size="lg" />

            <div class="flex flex-col justify-center py-2 pl-3">
                <div class="flex gap-2">
                    <p class="pb-1 text-sm font-bold">{{ $name }}</p>
                    <x-filament::badge>
                        {{$status}}
                    </x-filament::badge>
                </div>
                @if($address)
                <div class="flex flex-col items-start">
                    <p class="text-xs leading-5">{{Str::title($address?->kecamatan()->first()->name) }},
                        {{Str::title($address?->kota()->first()->name) }}, {{
                        Str::title($address?->provinsi()->first()->name) }}</p>
                </div>
                @else
                <p class="text-xs italic leading-5 text-gray-400">alamat warga tidak disetel</p>
                @endif
            </div>
        </div>

    </div>
</div>