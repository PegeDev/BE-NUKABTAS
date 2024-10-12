<?php

namespace App\Livewire\Mwcnu\SuratKeputusan;

use App\Filament\Components\UseSuratKeputusanForm;
use App\Models\Mwcnu;
use App\Models\SuratKeputusanMwcnu;
use Carbon\Carbon;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

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
                TextColumn::make("jenis_kepengurusan")
                    ->color(fn($state) => match ($state) {
                        "pengurus_mwc" => Color::Fuchsia,
                        "ranting_nu" => Color::Amber,
                        "anak_ranting" => Color::Blue,
                        "banom" => Color::Red,
                        "lembaga" => Color::Lime,
                        default => Color::Gray
                    })
                    ->formatStateUsing(fn($state) => Str::upper(str_replace("_", " ", $state)))
                    ->badge()
                    ->label("JENIS"),
                TextColumn::make('nomor_surat')
                    ->label("NOMOR SURAT"),
                TextColumn::make('end_khidmat')
                    ->formatStateUsing(function (SuratKeputusanMwcnu $record, $state) {
                        return new HtmlString(
                            Blade::render(
                                Carbon::parse($record->start_khidmat)->translatedFormat("d M Y") . " - " . Carbon::parse($record->end_khidmat)->translatedFormat("d M Y") . '<x-filament::badge size="sm" color="{{ $color }}">{{ $status }}</x-filament::badge>',
                                ["status" => Carbon::now()->gt(Carbon::parse($state)) ? 'SELESAI' : 'BERJALAN', "color" => Carbon::now()->gt(Carbon::parse($state)) ? 'danger' : 'info']
                            )
                        );
                    })
                    ->label("MASA KHIDMAT"),
                TextColumn::make('nomor_surat')
                    ->label("NOMOR SURAT"),
            ])
            ->headerActions([
                CreateAction::make("upload_sk")
                    ->label("Upload Surat Keputusan")
                    ->form(UseSuratKeputusanForm::schema($this->record))
                    ->icon("heroicon-m-arrow-up-tray")
                    ->visible(fn() => auth()->user()->id === $this->record->admin_id || auth()->user()->hasRole(['super_admin', 'admin_kabupaten']))
                    ->size(ActionSize::Small)
            ])
            ->filters([
                // ...
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->form(UseSuratKeputusanForm::schema($this->record, true))
                        ->icon("fluentui-document-edit-20-o")
                        ->size(ActionSize::Small)
                        ->after(fn() => $this->resetTable())
                        ->label("Edit SK"),
                    DeleteAction::make()
                        ->visible(fn() => auth()->user()->id === $this->record->admin_id || auth()->user()->hasRole(['super_admin', 'admin_kabupaten']))
                        ->label("Hapus SK")
                        ->size(ActionSize::Small)
                ])
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
