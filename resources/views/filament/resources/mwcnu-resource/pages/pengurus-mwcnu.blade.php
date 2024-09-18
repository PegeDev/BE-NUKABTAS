<div>
    <div class="mx-auto mb-5 md:w-fit">
        <x-filament::tabs @class(["flex-wrap items-center justify-center gap-2"])>
            <x-filament::tabs.item @class(["bg-slate-100"]) icon="heroicon-m-user-group"
                :active="$activeTab === 'semua'" wire:click="setTabs('semua')">
                Semua
                <x-slot name="badge">
                    {{$allCount}}
                </x-slot>
            </x-filament::tabs.item>
            <x-filament::tabs.item @class(["bg-slate-100"]) icon="heroicon-m-users" :active="$activeTab === 'mwc'"
                wire:click="setTabs('mwc')">
                Pengurus MWCNU
                <x-slot name="badge">
                    {{$pengurusCount}}
                </x-slot>
            </x-filament::tabs.item>
            <x-filament::tabs.item @class(["bg-slate-100"]) icon="ionicon-git-branch" :active="$activeTab === 'lembaga'"
                wire:click="setTabs('lembaga')">
                Lembaga
                <x-slot name="badge">
                    {{$lembagaCount}}
                </x-slot>
            </x-filament::tabs.item>
            <x-filament::tabs.item @class(["bg-slate-100"]) icon="ionicon-git-branch" :active="$activeTab === 'banom'"
                wire:click="setTabs('banom')">
                Banom
                <x-slot name="badge">
                    {{$banomCount}}
                </x-slot>
            </x-filament::tabs.item>
            <x-filament::tabs.item @class(["bg-slate-100"]) icon="ionicon-git-branch"
                :active="$activeTab === 'ranting_nu'" wire:click="setTabs('ranting_nu')">
                Ranting NU
                <x-slot name="badge">
                    {{$rantingNuCount}}
                </x-slot>
            </x-filament::tabs.item>
            <x-filament::tabs.item @class(["bg-slate-100"]) icon="ionicon-git-branch"
                :active="$activeTab === 'anak_ranting'" wire:click="setTabs('anak_ranting')">
                Anak Ranting
                <x-slot name="badge">
                    {{$anakRantingCount}}
                </x-slot>
            </x-filament::tabs.item>
        </x-filament::tabs>
    </div>

    <div class="mb-5 ml-auto w-fit">
        @if ($activeTab === 'mwc')
        {{$this->addPengurus()}}
        @elseif ($activeTab === 'lembaga')
        {{$this->addLembaga()}}
        @elseif ($activeTab === 'banom')
        {{$this->addBanom()}}
        @elseif ($activeTab === 'ranting_nu')
        {{$this->addRantingNu()}}
        @elseif ($activeTab === 'anak_ranting')
        {{$this->addAnakRanting()}}
        @endif
        <x-filament-actions::modals />
    </div>
    <div wire:target="setTabs" wire:loading.class="relative overflow-hidden rounded-md">
        <div wire:target="setTabs" wire:loading
            class="absolute inset-0 z-10 flex items-center justify-center bg-black/45 backdrop-blur-sm">
            <div class="flex flex-col items-center justify-center h-full">
                <x-filament::loading-indicator class="w-12 h-12 text-white" />
                <p class="text-lg font-semibold text-white">Loading...</p>
            </div>
        </div>

        {{ $this->table }}
    </div>
</div>