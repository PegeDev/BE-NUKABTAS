<?php

namespace App\Filament\Resources\MwcnuResource\Pages;

use App\Exports\JamaahExport;
use App\Filament\Resources\JamaahResource;
use App\Filament\Resources\MwcnuResource;
use App\Forms\Components\SelectList;
use App\Imports\JamaahImport;
use App\Models\Jemaah;
use App\Models\Mwcnu;
use App\Tables\Columns\ImageTextColumn;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\HtmlString;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Laravolt\Indonesia\Models\District;
use Livewire\Component;

class JamaahMwcnu extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static string $resource = MwcnuResource::class;

    protected static string $view = "filament.resources.mwcnu-resource.pages.jamaah-mwcnu";

    protected static ?string $title = 'Detail Kecamatan';

    public Mwcnu $record;

    public function table(Table $table): Table
    {
        // dd($this->record->jemaahs);
        return $table
            ->relationship(fn(): HasMany => $this->record->jemaahs())
            ->columns([
                ImageTextColumn::make("nama_lengkap")
                    ->state(fn($record) => $record)
                    ->searchable(true)
                    ->label("NAMA LENGKAP/NIK"),
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
                TextColumn::make('alamat_jemaah')
                    ->formatStateUsing(function ($state) {
                        return Str::title($state->provinsi()->first()->name);
                    })
                    ->description(function ($state) {
                        $findCity =  $state ? $state->kota()->first()->name : "-";
                        $findDistrict =  $state ? $state->kecamatan()->first()->name : "-";
                        return Str::title($findDistrict  . ", " .  $findCity);
                    })
                    ->placeholder('-')
                    ->weight(FontWeight::Medium)
                    ->label("ALAMAT")
                    ->size(TextColumnSize::Small),
                TextColumn::make('kepengurusan_type')
                    ->formatStateUsing(function ($state) {
                        return Str::upper($state->type ?? $state);
                    })
                    ->color(fn($state) => match ($state->type ?? $state) {
                        "Pengurus MWC" => Color::Fuchsia,
                        "Ranting" => Color::Cyan,
                        "Anak Ranting" => Color::Blue,
                        "Banom" => "warning",
                        "Lembaga" => "danger",
                        default => "gray"
                    })
                    ->badge()
                    ->weight(FontWeight::SemiBold)
                    ->label("KEPENGURUSAN")
                    ->size(TextColumnSize::Small),
            ])
            ->filters([
                SelectFilter::make("alamat_jemaah")
                    ->form([
                        Select::make("provinsi")
                            ->label("Provinsi")
                            ->options(fn() => Province::all()->pluck('name', 'code'))
                            ->searchable()
                            ->afterStateUpdated(function (Set $set) {
                                $set('kota', null);
                                $set('kecamatan', null);
                                $set('desa', null);
                            })
                            ->reactive(),
                        Select::make("kota")
                            ->label("Kota")
                            ->options(function (Get $get,) {
                                $find = City::where("province_code", $get('provinsi'));

                                if ($find) {
                                    return $find->pluck('name', 'code');
                                }
                            })
                            ->searchable()
                            ->afterStateUpdated(function (Set $set) {
                                $set('kecamatan', null);
                                $set('desa', null);
                            })
                            ->reactive(),
                        Select::make("kecamatan")
                            ->label("Kecamatan")
                            ->options(function (Get $get,) {
                                $find = District::where("city_code", $get('kota'));

                                if ($find) {
                                    return $find->pluck('name', 'code');
                                }
                            })
                            ->searchable()
                            ->afterStateUpdated(function (Set $set) {
                                $set('desa', null);
                            })
                            ->reactive(),
                        Select::make("desa")
                            ->label("Desa")
                            ->options(function (Get $get,) {
                                $find = Village::where("district_code", $get('kecamatan'));

                                if ($find) {
                                    return $find->pluck('name', 'code');
                                }
                            })
                            ->searchable()
                            ->reactive(),
                    ])

                    ->query(function (Builder $query, array $data): Builder {

                        $province_code = (string) $data["provinsi"];
                        $city_code = (string) $data["kota"];
                        $district_code = (string) $data["kecamatan"];
                        $village_code = (string) $data["desa"];

                        $query->whereHas('alamat_jemaah', function ($query) use ($province_code, $city_code, $district_code, $village_code) {
                            if ($province_code) {
                                $query->where('provinsi', $province_code);
                            }
                            if ($city_code) {
                                $query->where('kota', $city_code);
                            }
                            if ($district_code) {
                                $query->where('kecamatan', $district_code);
                            }
                            if ($village_code) {
                                $query->where('desa', $village_code);
                            }
                        });
                        return $query;
                    })
                    ->indicateUsing(function (array $data): ?array {
                        $indicators = [];

                        if ($data['provinsi'] ?? null) {
                            $find = Province::where("code", $data['provinsi'])->first();
                            $indicators[] = Indicator::make(Str::title($find->name))->removeField("provinsi");;
                        }

                        if ($data['kota'] ?? null) {
                            $find = City::where("code", $data['kota'])->first();
                            $indicators[] = Indicator::make(Str::title($find->name))->removeField("kota");
                        }

                        if ($data['kecamatan'] ?? null) {
                            $find = District::where("code", $data['kecamatan'])->first();
                            $indicators[] = Indicator::make(Str::title($find->name))->removeField("kecamatan");;
                        }

                        if ($data['desa'] ?? null) {
                            $find = Village::where("code", $data['desa'])->first();
                            $indicators[] = Indicator::make(Str::title($find->name))->removeField("desa");;
                        }

                        return  $indicators;
                    })

            ])
            ->actions([
                ActionGroup::make([
                    Action::make("detail")
                        ->label("Lihat Detail")
                        ->url(fn(Jemaah $record): string => JamaahResource::getUrl("detail", ["record" => $record->id]))
                        ->icon("heroicon-o-user"),
                    Action::make("edit")
                        ->label("Edit Profile")
                        ->url(fn(Jemaah $record): string => JamaahResource::getUrl("edit", ["record" => $record->id]))
                        ->visible(auth()->user()->can("update_jamaah") && auth()->user()->id === $this->record->admin_id)
                        ->icon("heroicon-o-pencil-square"),
                    Action::make("delete")
                        ->label("Hapus Warga")
                        ->color("danger")
                        ->visible(auth()->user()->can("delete_jamaah"))
                        ->successNotificationTitle("Data Warga Telah Di Hapus")
                        ->requiresConfirmation()
                        ->action(fn(Jemaah $record) => $record->delete())
                        ->icon("heroicon-o-trash")
                ])
                    ->dropdownPlacement("bottom-end")
            ])
            ->headerActions([
                Action::make("Import")
                    ->visible($this->record->admin_id === auth()->user()->id)
                    ->icon("heroicon-o-document-arrow-up")
                    ->color('info')
                    ->size(ActionSize::Large)
                    ->modalWidth('lg')
                    ->form([
                        Placeholder::make("template")
                            ->content(new HtmlString('<div class="flex items-center justify-between">
                            <p class="font-medium hover:underline text-primary">Template-Data-Warga.xlsx</p>
                            <a href="/template/template-data-peserta.xlsx" class="px-2 py-1 text-sm text-white rounded-md bg-primary" target="__blank">Download</a>
                            </div>'))

                            ->label('Template data warga'),
                        FileUpload::make("attachment")
                            ->hiddenLabel(true)
                            ->directory('import/jemaah/')
                            ->acceptedFileTypes(["application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/vnd.ms-excel"])
                    ])
                    ->action(
                        function (array $data) {
                            $file = public_path("storage/" . $data["attachment"]);
                            $import = Excel::import(new JamaahImport($this->record->id), $file);

                            if ($import) {
                                Notification::make()
                                    ->title("Data Jamaah berhasil diimport")
                                    ->success()
                                    ->send();
                            } else {
                                return null;
                            }
                        }
                    ),

                Action::make("Export")
                    ->icon("heroicon-o-document-arrow-down")
                    ->visible($this->record->admin_id === auth()->user()->id)
                    ->color('info')
                    ->size(ActionSize::Large)
                    ->action(
                        fn() => Excel::download(new JamaahExport($this->record->jemaahs), "warga-{$this->record->nama_kecamatan}.xlsx")
                    ),
                Action::make("buat_warga")
                    ->label("Buat Warga baru")
                    ->icon("heroicon-o-user-plus")
                    ->visible($this->record->admin_id === auth()->user()->id)
                    ->color('primary')
                    ->size(ActionSize::Large)
                    ->url(fn($record) => MwcnuResource::getUrl('buat-warga', ["record" => $this->record])),
            ]);
    }
}
