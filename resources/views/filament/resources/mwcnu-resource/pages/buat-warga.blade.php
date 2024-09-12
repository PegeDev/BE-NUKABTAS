<x-filament-panels::page>
    {{--
    <form wire:submit="create">
        {{ $this->form }}


    </form>
    <x-filament-actions::modals /> --}}

    @livewire(App\Livewire\MwcnuResource\CreateJamaah::class, ['record'=>$this->record])
</x-filament-panels::page>