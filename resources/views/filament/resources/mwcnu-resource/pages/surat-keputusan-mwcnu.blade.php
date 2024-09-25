<div class="grid md:grid-cols-12 gap-6">
    <div class="md:col-span-6">
        @livewire("mwcnu.surat-keputusan.pengajuan-sk", ["record" => $record])
    </div>

    <div class="md:col-span-6">
        @livewire("mwcnu.surat-keputusan.surat-keputusan", ["record" => $record])
    </div>

</div>