<?php

// dd(route(App\Filament\Resources\MwcnuResource::getUrl('detail'), ['record' => $record->id]));
?>
<x-filament-panels::page>

    <x-filament::tabs class="w-full" label="Content tabs">
        @foreach ($tabs as $item)
        <x-filament::tabs.item class="group" icon="{{ $item['icon'] }}"
            alpine-active="$wire.get('state') == '{{ strtolower($item['label']) }}'"
            :href="route($item['view'], ['record' => $record])" tag="a">
            {{ $item['label'] }}
        </x-filament::tabs.item>
        @endforeach

    </x-filament::tabs>
    <div>
        @if ($state === 'detail')
        @livewire(App\Livewire\DetailLengkapMwcnu::class, ['record' => $this->record])
        @elseif($state === 'warga')
        @livewire(App\Filament\Resources\MwcnuResource\Pages\JamaahMwcnu::class, ['record' => $this->record])
        @elseif($state === 'kepengurusan')
        @livewire(App\Filament\Resources\MwcnuResource\Pages\PengurusMwcnu::class, ['record' => $this->record])
        @endif
    </div>

</x-filament-panels::page>