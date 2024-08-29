<?php

namespace App\Filament\Resources\MwcnuResource\Pages;

use App\Exports\JamaahExport;
use App\Filament\Resources\MwcnuResource;
use App\Forms\Components\SelectList;
use App\Imports\JamaahImport;
use App\Models\Jemaah;
use Carbon\Carbon;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Livewire\Component;

class JamaahMwcnu extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static string $resource = MwcnuResource::class;

    protected static string $view = "filament.resources.mwcnu-resource.pages.jamaah-mwcnu";


    protected static ?string $title = 'Jamaah MWCNU';

    public $record;


    public function table(Table $table): Table
    {
        return $table
            ->relationship(fn(): HasMany => $this->record->jemaahs())
            ->inverseRelationship('jamaah')
            ->columns([
                ViewColumn::make("nama_lengkap")
                    ->label("NAMA LENGKAP/NIK")
                    ->view("tables.columns.image-text-column"),
                TextColumn::make('jenis_kelamin')
                    ->description(fn(Jemaah $record): string => Carbon::parse($record->tanggal_lahir)->age . " Tahun")
                    ->weight(FontWeight::Medium)
                    ->formatStateUsing(fn($state) => strtoupper($state))
                    ->label("GENDER/UMUR")
                    ->size(TextColumnSize::Small),
                TextColumn::make('telp')
                    ->description(fn(Jemaah $record): string => $record->email)
                    ->weight(FontWeight::Medium)
                    ->label("TELEPON/EMAIL")
                    ->size(TextColumnSize::Small),
                TextColumn::make('alamat_lengkap')
                    ->formatStateUsing(function ($state) {
                        // dd(json_decode($state, true));
                        $decode = json_decode($state, true);
                        $provinsi = Province::where("code", $decode["provinsi"])->first()->name ?? "";
                        return Str::title($provinsi);
                    })
                    ->description(function ($state) {
                        $decode = json_decode($state, true);
                        $findCity = City::where("code", $decode["kota"])->first()->name ?? "-";
                        $findDesa = Village::where("code", $decode["desa"])->first()->name ?? "-";
                        return Str::title($findDesa . ", " . str_replace("KABUPATEN", "Kab.", $findCity));
                    })
                    ->weight(FontWeight::Medium)
                    ->label("ALAMAT")
                    ->size(TextColumnSize::Small),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make("detail")
                        ->label("Profile detail")
                        ->icon("heroicon-o-user-circle"),
                    EditAction::make("edit")
                        ->label("Edit")
                        ->icon("heroicon-o-pencil-square")
                ])
                    ->dropdownPlacement("bottom-end")
            ])
            ->bulkActions([])
            ->headerActions([
                Action::make("Import")
                    ->icon("heroicon-o-document-arrow-up")
                    ->color('info')
                    ->form([
                        FileUpload::make("attachment")
                            ->acceptedFileTypes(["application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/vnd.ms-excel"])
                    ])
                    ->action(
                        function (array $data) {
                            $file = public_path('storage/' . $data["attachment"]);
                            Excel::import(new JamaahImport($this->record->id), $file);
                        }
                    ),

                Action::make("Export")
                    ->icon("heroicon-o-document-arrow-down")
                    ->color('info')
                    ->action(
                        fn() => Excel::download(new JamaahExport($this->record->jemaahs), "Jamaah-{$this->record->nama_kecamatan}.xlsx")
                    ),

                ActionGroup::make([
                    Action::make("buat_peserta")
                        ->label("Buat Peserta baru")
                        ->icon("heroicon-o-user-plus")
                        ->url(function () {
                            return route('filament.dashboard.resources.data-kecamatan.create-jamaah', ['record' => $this->record]);
                        }),
                    Action::make("Pilih Kepengurusan dari daftar")
                        ->icon("heroicon-o-user-circle")
                        ->form([
                            SelectList::make("kepengurusan")
                                ->options(Jemaah::all()->toArray())
                        ])
                        ->action(function (array $data) {
                            $formatedData = collect($data["kepengurusan"])->map(function ($item) {
                                return [
                                    "jemaah_id" => $item["id"],
                                    "jabatan" => "",
                                    "mwcnu_id" => $this->record->id
                                ];
                            });
                            // dd($formatedData->toArray());
                            return $this->record->pengurus()->createMany($formatedData->toArray());
                        })
                        ->modalWidth("xl")
                ])
                    ->button()
                    ->size(ActionSize::ExtraLarge)
                    ->label("Tambah Jamaah")
                    ->icon("heroicon-o-plus")
                    ->dropdownPlacement('bottom-end')
            ]);
    }
}
