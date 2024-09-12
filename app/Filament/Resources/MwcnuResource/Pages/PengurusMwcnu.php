<?php

namespace App\Filament\Resources\MwcnuResource\Pages;

use App\Filament\Resources\MwcnuResource;
use App\Forms\Components\SelectList;
use App\Models\Mwcnu;
use App\Models\PengurusMwcnu as ModelsPengurusMwcnu;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Contracts\HasRecord;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class PengurusMwcnu extends Page implements HasForms, HasTable, HasActions
{
    use InteractsWithActions;
    use InteractsWithTable;


    protected static string $resource = MwcnuResource::class;

    protected static string $view = 'filament.resources.mwcnu-resource.pages.pengurus-mwcnu';

    public $activeTab = 'all';

    public $pengurusCount = 0, $allCount = 0, $record;



    public function table(Table $table): Table
    {

        return $table
            ->query(ModelsPengurusMwcnu::query())
            ->columns([
                TextColumn::make("nama_lengkap")
            ]);
    }


    public function addPengurus(): Action
    {
        return Action::make("addPengurus")
            ->icon("heroicon-m-plus")
            ->label("Tambah Pengurus")
            ->form([
                SelectList::make("jemaah_id")
                    ->label("Warga")
                    ->options(fn() => $this->record->jemaahs()->with('alamat_jemaah')->get())
                    ->placeholder("Pilih Warga"),

            ])
            ->modalWidth("md")
            ->action(function () {
                $kepengurusan = $this->record->kepengurusan;

                if (!$kepengurusan) {
                    $kepengurusan = $this->record->kepengurusan->create([
                        'mwcnu_id' => $this->record->id
                    ]);
                }
                dd($kepengurusan);
            });
    }
}
