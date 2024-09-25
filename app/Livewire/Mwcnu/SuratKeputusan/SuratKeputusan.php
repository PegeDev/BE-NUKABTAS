<?php

namespace App\Livewire\Mwcnu\SuratKeputusan;

use App\Models\Mwcnu;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratKeputusan extends Component implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;

    public Mwcnu $record;

    public function table(Table $table): Table
    {
        return $table
            ->relationship(fn(): HasMany => $this->record->surat_keputusan_mwcnu())
            ->heading("Surat Keputusan")
            ->emptyStateIcon("ionicon-documents-outline")
            ->emptyStateHeading("Belum ada Surat Keputusan")
            ->columns([
                TextColumn::make('name'),
            ])
            ->headerActions([
                CreateAction::make("upload_sk")
                    ->label("Upload Surat Keputusan")
                    ->form([
                        TextInput::make("nomor_surat")
                            ->label("Nomor Surat")
                            ->required(),

                        Grid::make()
                            ->columns([
                                "md" => 2
                            ])
                            ->schema([
                                DatePicker::make("start_khidmat")
                                    ->required()
                                    ->label("Tanggal Mulai"),
                                DatePicker::make("end_khidmat")
                                    ->required()
                                    ->label("Tanggal Berhenti"),
                            ]),
                        FileUpload::make("file_surat")
                            ->previewable(false)
                            ->required()
                            ->label("File Surat Keputusan")
                    ])
                    ->icon("heroicon-m-arrow-up-tray")
                    ->size(ActionSize::Small)
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }


    public function render()
    {
        return view('livewire.mwcnu.surat-keputusan.surat-keputusan');
    }
}
