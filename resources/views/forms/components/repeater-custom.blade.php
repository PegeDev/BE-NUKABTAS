@php
    $statePath = $getStatePath();
@endphp


<x-dynamic-component {{ $attributes }} :component="$getFieldWrapperView()" :field="$field">
    <div class="">
        @foreach ($getState() as $item)
            <div>
                {{ $item }}
            </div>
        @endforeach
    </div>

    <x-filament::button x-data="" x-on:click="$dispatch('open-modal', { id: '{{ $statePath }}' })">
        Tambah Item
    </x-filament::button>

    <div x-data="{
        state: [],
        input: $wire.$entangle('{{ $statePath }}'),
        items: [],
        addItem() {
    
            this.items.push(this.input);
    
    
            this.state = this.items;
    
            for (components in this.input) {
                console.log(components);
                this.input.set(components, '');
            }
    
            {{-- console.log('child', @js($getChildComponentContainers())); --}}
    
            $dispatch('close-modal', { id: '{{ $statePath }}' });
        },
        removeItem(index) {
            this.items.splice(index, 1);
            this.state = items;
        }
    }">

        <x-filament::modal id="{{ $statePath }}">
            <x-slot name="heading">
                Tambah {{ $getLabel() }}
            </x-slot>
            @foreach ($getChildComponentContainer()->getComponents() as $component)
                <div class="mb-4">
                    {{ $component }}
                </div>
            @endforeach
            <x-slot name="footer">
                <div class="grid grid-cols-2 gap-4">
                    <x-filament::button color="gray"
                        x-on:click="$dispatch('close-modal', { id: '{{ $statePath }}' })">
                        Batal
                    </x-filament::button>
                    <x-filament::button color="primary" x-on:click="addItem()">
                        Simpan
                    </x-filament::button>
                </div>
            </x-slot>
        </x-filament::modal>
    </div>
</x-dynamic-component>
