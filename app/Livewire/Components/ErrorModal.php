<?php

namespace App\Livewire\Components;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Livewire\Attributes\On;

class ErrorModal extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public $errorsData;

    public function errorModalAction(): Action
    {
        return Action::make('errorModal')
            ->modal()
            ->modalIcon('fluentui-text-bullet-list-square-warning-16-o')
            ->modalIconColor('danger')
            ->modalWidth('md')
            ->modalHeading('Error Detail')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel("Tutup")
            ->form([
                Textarea::make('error')
                    ->readOnly()
                    ->autosize(false)
                    ->hiddenLabel(true)
                    ->rows(12)
                    ->default(fn() => $this->errorsData)
            ]);
    }

    #[On('error-modal')]
    public function triggerErrorModalAction($errorsData)
    {
        $this->errorsData   = $errorsData;
        $this->mountAction('errorModal');
    }

    public function render()
    {
        return view('livewire.components.error-modal');
    }
}
