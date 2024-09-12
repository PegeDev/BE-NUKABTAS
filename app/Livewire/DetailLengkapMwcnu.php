<?php

namespace App\Livewire;

use App\Enums\MwcnuStatus as EnumsMwcnuStatus;
use App\Models\FormMwcnu;
use App\Models\Mwcnu;
use App\Models\MwcnuStatus;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

class DetailLengkapMwcnu extends Component implements HasForms
{

    use InteractsWithForms;

    public Mwcnu $record;

    public $isOpen = false, $statusEnabled = false, $isApproved = false, $msg = null;


    public function mount(Mwcnu $record)
    {
        $this->record = $record;
        $this->isOpen = $this->record->form_mwcnu->is_enabled ?? false;
    }

    public function approveStatus()
    {

        $this->record->status()->create([
            'mwcnu_id' => $this->record->id,
            'message' => $this->msg,
            'status' => EnumsMwcnuStatus::DISETUJUI,
        ]);

        $this->dispatch('close-modal', id: 'form-status');

        $adminKecamatan = User::find($this->record->admin_id);

        $recipientRole = User::role(['super_admin', 'admin_kabupaten'])->get();

        $recipients = collect([$adminKecamatan])->merge($recipientRole)->filter();

        if ($recipients->isNotEmpty()) {
            Notification::make()
                ->title("Kecamatan {$this->record->nama_kecamatan} Disetujui")
                ->success()
                ->send()
                ->sendToDatabase($recipients);
        }
    }
    public function buatForm()
    {
        $this->record->form_mwcnu()->create([
            "is_enabled" => true,
            "code" => Str::random(36)
        ]);
        $this->record->save();
        $this->isOpen = true;
    }


    public function handleStatusForm(bool $isOpen)
    {

        $this->record->form_mwcnu->is_enabled = $isOpen;
        $this->record->form_mwcnu->save();
    }



    public function render(): View
    {

        return view('livewire.detail-lengkap-mwcnu');
    }
}
