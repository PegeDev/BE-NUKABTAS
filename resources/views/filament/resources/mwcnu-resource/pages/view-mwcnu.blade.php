<x-filament-panels::page>

    <x-filament::tabs class="w-full" label="Content tabs">


        @foreach ($tabs as $item)
            <x-filament::tabs.item class="group" icon="{{ $item['icon'] }}"
                alpine-active="$wire.get('state') == '{{ strtolower($item['label']) }}'" :href="route($item['view'], ['record' => $record])" tag="a">
                {{ $item['label'] }}
            </x-filament::tabs.item>
        @endforeach


    </x-filament::tabs>

    <div>
        @if ($state === 'detail')
            @livewire(App\Livewire\DetailMwcnu::class, ['record' => $this->record])
        @elseif($state === 'jamaah')
            @livewire(App\Filament\Resources\MwcnuResource\Pages\JamaahMwcnu::class, ['record' => $this->record])
        @endif
    </div>

</x-filament-panels::page>
