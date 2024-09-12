<div>
    <div class="mx-auto mb-5 w-fit">
        <x-filament::tabs>
            <x-filament::tabs.item icon="heroicon-m-users" :active="$activeTab === 'all'"
                wire:click="$set('activeTab', 'all')">
                Semua
                <x-slot name="badge">
                    {{$allCount}}
                </x-slot>
            </x-filament::tabs.item>
            <x-filament::tabs.item icon="heroicon-m-user-group" :active="$activeTab === 'pengurus'"
                wire:click="$set('activeTab', 'pengurus')">
                Pengurus
                <x-slot name="badge">
                    {{0}}
                </x-slot>
            </x-filament::tabs.item>
            <x-filament::tabs.item icon="ionicon-git-branch" :active="$activeTab === 'lembaga'"
                wire:click="$set('activeTab', 'lembaga')">
                Lembaga
                <x-slot name="badge">
                    {{0}}
                </x-slot>
            </x-filament::tabs.item>
            <x-filament::tabs.item icon="ionicon-git-branch" :active="$activeTab === 'banom'"
                wire:click="$set('activeTab', 'banom')">
                Banom
                <x-slot name="badge">
                    {{0}}
                </x-slot>
            </x-filament::tabs.item>
            <x-filament::tabs.item icon="ionicon-git-branch" :active="$activeTab === 'ranting_nu'"
                wire:click="$set('activeTab', 'ranting_nu')">
                Ranting NU
                <x-slot name="badge">
                    {{0}}
                </x-slot>
            </x-filament::tabs.item>
            <x-filament::tabs.item icon="ionicon-git-branch" :active="$activeTab === 'anak_ranting'"
                wire:click="$set('activeTab', 'anak_ranting')">
                Anak Ranting
                <x-slot name="badge">
                    {{0}}
                </x-slot>
            </x-filament::tabs.item>
        </x-filament::tabs>
    </div>
    <div class="mb-5 ml-auto w-fit">
        {{$this->addPengurus()}}
        <x-filament-actions::modals />
    </div>
    <div>

        {{ $this->table }}
    </div>
</div>