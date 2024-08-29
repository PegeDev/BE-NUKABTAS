<?php

namespace App\Livewire;

use Exception;
use Filament\Forms\Concerns\InteractsWithForms;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Illuminate\Support\Facades\Log;

class DetailMwcnu extends Component implements HasForms, HasInfolists
{

    use InteractsWithForms;
    use InteractsWithInfolists;

    public $record;

    public $isOpen = false;

    public function render(): View
    {
        return view('livewire.detail-mwcnu');
    }

    public function mount($record)
    {

        $this->record = $record;
        $this->isOpen = $this->record->form_mwcnu->is_enabled !== 0 ? true : false;
    }


    public function handleStatusForm(string $bool)
    {

        $this->record->form_mwcnu->is_enabled = $bool ? "1" : "0";
        $this->record->form_mwcnu->save();
    }
}
