<?php

namespace App\Livewire;

use App\Models\FormMwcnu;
use App\Models\Mwcnu;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class DetailMwcnu extends Component implements HasForms
{

    use InteractsWithForms;

    public Mwcnu $record;

    public $isOpen = false;


    public function mount(Mwcnu $record)
    {
        $this->record = $record;
        $this->isOpen = $this->record->form_mwcnu->is_enabled ?? false;
    }



    public function buatForm()
    {
        $create = FormMwcnu::create([
            "mwcnu_id" => $this->record->id,
            "is_enabled" => "1",
            "code" => Str::random(40)
        ]);
        return $create;
    }


    public function handleStatusForm(string $bool)
    {

        $this->record->form_mwcnu->is_enabled = $bool;
        $this->record->form_mwcnu->save();
    }



    public function render(): View
    {

        return view('livewire.detail-mwcnu');
    }
}
