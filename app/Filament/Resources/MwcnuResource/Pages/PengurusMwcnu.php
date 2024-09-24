<?php

namespace App\Filament\Resources\MwcnuResource\Pages;

use App\Enums\Position;
use App\Filament\Resources\MwcnuResource;
use App\Models\BanomMaster;
use App\Models\LembagaMaster;
use App\Models\Mwcnu;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;
use Laravolt\Indonesia\Models\District;

class PengurusMwcnu extends Page implements HasForms, HasTable, HasActions
{
    use InteractsWithActions;
    use InteractsWithTable;

    protected static string $resource = MwcnuResource::class;

    protected static string $view = 'filament.resources.mwcnu-resource.pages.pengurus-mwcnu';

    public $activeTab = 'semua';

    public Mwcnu $record;

    public $pengurusCount = 0, $allCount = 0, $banomCount = 0, $lembagaCount = 0, $rantingNuCount = 0, $anakRantingCount = 0;

    public bool $isTabAll = false;


    public function mount(): void
    {
        $this->record->kepengurusan()->firstOrCreate([
            'mwcnu_id' => $this->record->id
        ], [
            'mwcnu_id' => $this->record->id
        ]);

        $this->pengurusCount = $this->record->kepengurusan->pengurus_mwcnu()->count() ?? 0;

        $this->lembagaCount = $this->record->kepengurusan->lembaga_mwcnu()->count() ?? 0;

        $this->banomCount = $this->record->kepengurusan->banom_mwcnu()->count() ?? 0;

        $this->rantingNuCount = $this->record->kepengurusan->ranting_nu()->count() ?? 0;

        $this->anakRantingCount = $this->record->kepengurusan->anak_ranting()->count() ?? 0;

        $this->allCount = $this->record->kepengurusan->all_pengurus()->count() ?? 0;

        $this->isTabAll = $this->activeTab === 'semua';
    }



    public function setTabs(string $activeTab): void
    {
        $this->activeTab = $activeTab;
        $this->isTabAll = $activeTab === 'semua';

        $this->resetTable();
    }

    public function table(Table $table): Table
    {
        $query = $this->record->kepengurusan->all_pengurus();


        if ($this->activeTab == 'mwc') {
            $query = $this->record->kepengurusan->pengurus_mwcnu();
        }
        if ($this->activeTab == 'lembaga') {
            $query = $this->record->kepengurusan->lembaga_mwcnu();
        }
        if ($this->activeTab == 'banom') {
            $query = $this->record->kepengurusan->banom_mwcnu();
        }
        if ($this->activeTab == 'ranting_nu') {
            $query = $this->record->kepengurusan->ranting_nu();
        }
        if ($this->activeTab == 'anak_ranting') {
            $query = $this->record->kepengurusan->anak_ranting();
        }

        return $table
            ->query(fn() => $query)
            ->emptyStateHeading("Data pengurus tidak ditemukan")
            ->striped()
            ->heading("Data Pengurus " . Str::title(str_replace('_', ' ', $this->activeTab)) . " " . $this->record->nama_kecamatan)
            ->columns([
                ViewColumn::make("jemaah.nama_lengkap")
                    ->state(fn($record) => $record)
                    ->searchable($this->isTabAll ? false : ["jemaah.nama_lengkap", "jemaah.nik"])
                    ->label("NAMA LENGKAP/NIK")
                    ->view("tables.columns.image-text-column"),
                TextColumn::make($this->isTabAll ? 'jenis_kelamin' : 'jemaah.jenis_kelamin')
                    ->description(fn($record) => Carbon::parse($record->tanggal_lahir)->age . " tahun")
                    ->weight(FontWeight::Medium)
                    ->formatStateUsing(fn($state) => strtoupper($state))
                    ->label("GENDER/UMUR")
                    ->size(TextColumnSize::Small),
                TextColumn::make($this->isTabAll ? 'telp' : 'jemaah.telp')
                    ->description(function ($record) {
                        if ($record->jemaah) {
                            return $record->email;
                        } else {
                            return $record->email;
                        }
                    })
                    ->formatStateUsing(fn($state) => "+" . $state ?? "-")
                    ->weight(FontWeight::Medium)
                    ->label("TELEPON/EMAIL")
                    ->size(TextColumnSize::Small),
                TextColumn::make($this->isTabAll ? 'alamat_jemaah' : 'jemaah.alamat_jemaah')
                    ->formatStateUsing(function ($state) {
                        return Str::title($state->provinsi()->first()->name);
                    })
                    ->description(function ($state) {
                        $findCity =  $state ? str_replace("KABUPATEN ", "Kab. ", $state->kota()->first()->name ?? "-") : "-";
                        $findDistrict =  $state ? $state->kecamatan()->first()->name : "-";
                        return Str::title($findDistrict  . ", " .  $findCity);
                    })
                    ->placeholder('-')
                    ->weight(FontWeight::Medium)
                    ->label("ALAMAT")
                    ->size(TextColumnSize::Small),
                TextColumn::make($this->isTabAll ? 'kepengurusan_type.nama_desa' : 'jemaah.kepengurusan_type.nama_desa')
                    ->label("DESA")
                    ->visible($this->activeTab == 'anak_ranting' || $this->activeTab == "ranting_nu")
                    ->badge()
                    ->placeholder("-"),
                TextColumn::make($this->isTabAll ? 'kepengurusan_type' : 'jemaah.kepengurusan_type')
                    ->formatStateUsing(fn($state) => Str::upper($state->type))
                    ->description(function ($state) {
                        return Str::title(($state->jabatan ? preg_replace("/_/", " ", $state->jabatan) . (isset($state->posisi) ? ", " : null) : null) . ($state->posisi ?? null));
                    })
                    ->label("KEPENGURUSAN")
                    ->color(fn($state) => match ($state->type) {
                        "Pengurus MWC" => Color::Fuchsia,
                        "Ranting" => Color::Cyan,
                        "Anak Ranting" => Color::Blue,
                        "Banom" => "warning",
                        "Lembaga" => "danger",
                        default => "gray"
                    })
                    ->badge()
                    ->placeholder("-")

            ])
            ->actions([
                ActionGroup::make([
                    ActionsAction::make("deleteKepengurusan")
                        ->requiresConfirmation()
                        ->visible(fn($record) => ! $this->isTabAll)
                        ->icon("heroicon-o-trash")
                        ->label("Hapus")
                        ->visible($this->record->admin_id === auth()->user()->id)
                        ->successNotification(
                            function ($record) {
                                Notification::make()
                                    ->success()
                                    ->title('Pengurus dihapus')
                                    ->body("Pengurus {$record->nama_lengkap} di hapus");
                            }
                        )
                        ->action(function ($record) {
                            $record->delete();
                            $this->mount();
                        })
                ]),
            ]);
    }


    public static function getCleanOptionString(Model $model): string
    {
        return view('forms.components.option-warga')
            ->with('name', $model?->nama_lengkap)
            ->with('address', $model?->alamat_jemaah)
            ->with('image', $model->profile_picture ? Storage::url($model?->profile_picture) : ($model?->jenis_kelamin === "laki-laki" ? "/avatar_male.png" : "/avatar_female.png"))
            ->with('status', $model?->kepengurusan_type)
            ->render();
    }

    public static function getCleanOptionLembaga(Model $model): string
    {
        return view('forms.components.option-lembaga')
            ->with('name', $model?->name)
            ->with('logo', $model?->logo ? Storage::url($model?->logo) : "/logo-nu.png")
            ->render();
    }

    public function addPengurus(): Action
    {
        return Action::make("addPengurus")

            ->icon("heroicon-m-plus")
            ->successNotificationTitle("Berhasil menambah Pengurus MWC")
            ->label("Tambah Pengurus")
            ->form([
                Select::make("jemaah_id")
                    ->allowHtml()
                    ->required()
                    ->searchable()
                    ->options(function () {
                        $users = $this->record->jemaahs()
                            ->whereNotIn('id', $this->record->kepengurusan->all_pengurus()->pluck('id'))
                            ->get();

                        return $users->mapWithKeys(function ($user) {
                            return [$user->getKey() => static::getCleanOptionString($user)];
                        })->toArray();
                    })
                    ->getSearchResultsUsing(function (string $search) {
                        $users = $this->record->jemaahs()->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%")->limit(50)->get();

                        return $users->mapWithKeys(function ($user) {
                            return [$user->getKey() => static::getCleanOptionString($user)];
                        })->toArray();
                    })
                    ->label('Pilih / Cari Warga')
                    ->getOptionLabelUsing(function ($value): string {
                        $user = $this->record->jemaahs()->find($value);

                        return static::getCleanOptionString($user);
                    })
                    ->label("Warga"),
                Grid::make()
                    ->schema([
                        Select::make("posisi")
                            ->options(Position::class)
                            ->live()
                            ->required()
                            ->default(Position::Mustasyar)
                            ->columnSpan(fn($state) => $state == Position::Mustasyar ? 2 : 1)
                            ->native(false)
                            ->preload()
                            ->label("Posisi"),
                        Select::make("jabatan")
                            ->live()
                            ->required()
                            ->options(function (Get $get) {
                                if ($get('posisi') == Position::Syuriyah) {
                                    return Position::Syuriyah->getJabatan($get('posisi'));
                                }
                                return Position::Tanfidziyah->getJabatan($get('posisi'));
                            })
                            ->preload()
                            ->hidden(function (Get $get): bool {
                                return $get('posisi') == Position::Mustasyar;
                            })
                            ->native(false)
                            ->label("Jabatan"),
                    ])
                    ->columns(2),
                Grid::make()
                    ->schema([
                        TextInput::make("start_khidmat")
                            ->placeholder("2020")
                            ->required()
                            ->label("Tahun Mulai"),
                        TextInput::make("end_khidmat")
                            ->placeholder("2022")
                            ->required()
                            ->label("Tahun Selesai"),

                    ])->columns(2)

            ])
            ->modalWidth("md")
            ->action(function ($data) {
                $kepengurusan = $this->record->kepengurusan()->firstOrCreate([
                    'mwcnu_id' => $this->record->id
                ], [
                    'mwcnu_id' => $this->record->id
                ]);

                $kepengurusan->pengurus_mwcnu()->create([
                    'jemaah_id' => $data['jemaah_id'],
                    'jabatan' => $data['jabatan'] ?? '',
                    'posisi' => $data['posisi'],
                    "kepengurusan_id" => $this->record->kepengurusan->id,
                    'masa_khidmat' => [
                        'start' => $data['start_khidmat'],
                        'end' => $data['end_khidmat'],
                    ],
                ]);


                $kepengurusan->save();

                $this->record->save();

                $this->mount();
            });
    }

    public function addLembaga(): Action
    {
        return Action::make("addLembaga")

            ->icon("heroicon-m-plus")
            ->label("Tambah Pengurus")
            ->successNotificationTitle("Berhasil menambah Pengurus Lembaga")
            ->form([
                Select::make("jemaah_id")
                    ->allowHtml()
                    ->required()
                    ->searchable()
                    ->options(function () {
                        $users = $this->record->jemaahs()
                            ->whereNotIn('id', $this->record->kepengurusan->all_pengurus()->pluck('id'))
                            ->get();

                        return $users->mapWithKeys(function ($user) {
                            return [$user->getKey() => static::getCleanOptionString($user)];
                        })->toArray();
                    })
                    ->getSearchResultsUsing(function (string $search) {
                        $users = $this->record->jemaahs()->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%")->limit(50)->get();

                        return $users->mapWithKeys(function ($user) {
                            return [$user->getKey() => static::getCleanOptionString($user)];
                        })->toArray();
                    })
                    ->getOptionLabelUsing(function ($value): string {
                        $user = $this->record->jemaahs()->find($value);

                        return static::getCleanOptionString($user);
                    })
                    ->label("Warga"),

                Select::make("lembaga_id")
                    ->allowHtml()
                    ->searchable()
                    ->required()
                    ->options(
                        function () {
                            $lembaga = LembagaMaster::all();

                            return $lembaga->mapWithKeys(function ($lembaga) {
                                return [$lembaga->getKey() => static::getCleanOptionLembaga($lembaga)];
                            })->toArray();
                        }

                    )
                    ->getSearchResultsUsing(function (string $search) {
                        $lembaga = LembagaMaster::where('name', 'like', "%{$search}%")->limit(50)->get();

                        return $lembaga->mapWithKeys(function ($lembaga) {
                            return [$lembaga->getKey() => static::getCleanOptionLembaga($lembaga)];
                        })->toArray();
                    })
                    ->getOptionLabelUsing(function ($value): string {
                        $lembaga = LembagaMaster::find($value)->first();

                        return static::getCleanOptionLembaga($lembaga);
                    })
                    ->label("Nama Lembaga"),

                Select::make("jabatan")
                    ->live()
                    ->options([
                        "ketua" => "Ketua",
                        "wakil_ketua" => "Wakil Ketua",
                        "sekretaris" => "Sekretaris",
                        "wakil_sekretaris" => "Wakil Sekretaris",
                        "bendahara" => "Bendahara",
                        "wakil_bendahara" => "Wakil Bendahara",
                    ])
                    ->required()
                    ->preload()
                    ->native(false)
                    ->label("Jabatan"),
                Grid::make()
                    ->schema([
                        TextInput::make("start_khidmat")
                            ->placeholder("2020")
                            ->label("Tahun Mulai"),
                        TextInput::make("end_khidmat")
                            ->placeholder("2022")
                            ->label("Tahun Selesai"),

                    ])->columns(2)


            ])
            ->modalWidth("md")
            ->action(function ($data) {
                $kepengurusan = $this->record->kepengurusan;

                $kepengurusan->lembaga_mwcnu()->create([
                    "jabatan" => $data['jabatan'],
                    "jemaah_id" => $data['jemaah_id'],
                    "kepengurusan_id" => $kepengurusan->id,
                    "lembaga_id" => $data['lembaga_id'],
                    'masa_khidmat' => [
                        'start' => $data['start_khidmat'],
                        'end' => $data['end_khidmat'],
                    ],
                ]);



                $kepengurusan->save();

                $this->record->save();

                $this->mount();
            });
    }

    public function addBanom(): Action
    {
        return Action::make("addBanom")

            ->icon("heroicon-m-plus")
            ->label("Tambah Pengurus")
            ->form([
                Select::make("jemaah_id")
                    ->allowHtml()
                    ->required()
                    ->searchable()
                    ->options(function () {
                        $users = $this->record->jemaahs()
                            ->whereNotIn('id', $this->record->kepengurusan->all_pengurus()->pluck('id'))
                            ->get();

                        return $users->mapWithKeys(function ($user) {
                            return [$user->getKey() => static::getCleanOptionString($user)];
                        })->toArray();
                    })
                    ->getSearchResultsUsing(function (string $search) {
                        $users = $this->record->jemaahs()->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%")->limit(50)->get();

                        return $users->mapWithKeys(function ($user) {
                            return [$user->getKey() => static::getCleanOptionString($user)];
                        })->toArray();
                    })
                    ->getOptionLabelUsing(function ($value): string {
                        $user = $this->record->jemaahs()->find($value);

                        return static::getCleanOptionString($user);
                    })
                    ->label("Warga"),

                Select::make("banom_id")
                    ->allowHtml()
                    ->required()
                    ->searchable()
                    ->options(
                        function () {
                            $lembaga = BanomMaster::all();

                            return $lembaga->mapWithKeys(function ($lembaga) {
                                return [$lembaga->getKey() => static::getCleanOptionLembaga($lembaga)];
                            })->toArray();
                        }

                    )
                    ->getSearchResultsUsing(function (string $search) {
                        $lembaga = BanomMaster::where('name', 'like', "%{$search}%")->limit(50)->get();

                        return $lembaga->mapWithKeys(function ($lembaga) {
                            return [$lembaga->getKey() => static::getCleanOptionLembaga($lembaga)];
                        })->toArray();
                    })
                    ->getOptionLabelUsing(function ($value): string {
                        $lembaga = BanomMaster::find($value)->first();

                        return static::getCleanOptionLembaga($lembaga);
                    })
                    ->label("Nama Banom"),

                Select::make("jabatan")
                    ->live()
                    ->required()
                    ->options([
                        "anggota" => "Anggota",
                        "ketua" => "Ketua",
                        "wakil ketua" => "Wakil Ketua",
                        "sekretaris" => "Sekretaris",
                        "wakil sekretaris" => "Wakil Sekretaris",
                        "bendahara" => "Bendahara",
                        "wakil bendahara" => "Wakil Bendahara",
                    ])
                    ->preload()
                    ->required()
                    ->native(false)
                    ->label("Jabatan"),
                Grid::make()
                    ->schema([
                        TextInput::make("start_khidmat")
                            ->placeholder("2020")
                            ->required()
                            ->label("Tahun Mulai"),
                        TextInput::make("end_khidmat")
                            ->placeholder("2022")
                            ->required()
                            ->label("Tahun Selesai"),

                    ])->columns(2)


            ])
            ->modalWidth("md")
            ->successNotificationTitle("Berhasil menambah Pengurus Banom")
            ->action(function ($data) {
                $kepengurusan = $this->record->kepengurusan()->firstOrCreate([
                    'mwcnu_id' => $this->record->id
                ], [
                    'mwcnu_id' => $this->record->id
                ]);

                $kepengurusan->banom_mwcnu()->create([
                    "jabatan" => $data['jabatan'],
                    "jemaah_id" => $data['jemaah_id'],
                    "kepengurusan_id" => $kepengurusan->id,
                    "banom_id" => $data['banom_id'],
                    'masa_khidmat' => [
                        'start' => $data['start_khidmat'],
                        'end' => $data['end_khidmat'],
                    ],
                ]);


                $kepengurusan->save();

                $this->record->save();

                $this->mount();
            });
    }

    public function addRantingNu(): Action
    {
        return Action::make("addRantingNU")

            ->icon("heroicon-m-plus")
            ->label("Tambah Pengurus")
            ->form([
                Select::make("jemaah_id")
                    ->allowHtml()
                    ->required()
                    ->searchable()
                    ->options(function () {
                        $users = $this->record->jemaahs()
                            ->whereNotIn('id', $this->record->kepengurusan->all_pengurus()->pluck('id'))
                            ->get();

                        return $users->mapWithKeys(function ($user) {
                            return [$user->getKey() => static::getCleanOptionString($user)];
                        })->toArray();
                    })
                    ->getSearchResultsUsing(function (string $search) {
                        $users = $this->record->jemaahs()->where('nama_lengkap', 'like', "%{$search}%")->limit(50)->get();

                        return $users->mapWithKeys(function ($user) {
                            return [$user->getKey() => static::getCleanOptionString($user)];
                        })->toArray();
                    })
                    ->getOptionLabelUsing(function ($value): string {
                        $user = $this->record->jemaahs()->find($value);

                        return static::getCleanOptionString($user);
                    })
                    ->label("Warga"),
                Select::make("village_id")
                    ->options(function () {
                        $kecamatan = District::where("name", "like", "%{$this->record->nama_kecamatan}%")->first();

                        return $kecamatan->villages->pluck('name', 'id');
                    })
                    ->native(false)
                    ->required()
                    ->preload()
                    ->label("Desa"),
                Grid::make()
                    ->schema([
                        Select::make("posisi")
                            ->required()
                            ->options(Position::class)
                            ->default(Position::Syuriyah)
                            ->disableOptionWhen(fn(string $value): bool => $value === Position::Mustasyar->getLabel())
                            ->native(false)
                            ->preload()
                            ->label("Posisi"),
                        Select::make("jabatan")
                            ->live()
                            ->options(function (Get $get) {
                                if ($get('posisi') == Position::Syuriyah) {
                                    return Position::Syuriyah->getJabatan($get('posisi'));
                                } elseif ($get('posisi') == Position::Tanfidziyah) {
                                    return Position::Tanfidziyah->getJabatan($get('posisi'));
                                }
                                return null;
                            })
                            ->preload()
                            ->native(false)
                            ->label("Jabatan"),
                    ])
                    ->columns(2),
                Grid::make()
                    ->schema([
                        TextInput::make("start_khidmat")
                            ->required()
                            ->placeholder("2020")
                            ->label("Tahun Mulai"),
                        TextInput::make("end_khidmat")
                            ->required()
                            ->placeholder("2022")
                            ->label("Tahun Selesai"),

                    ])->columns(2)

            ])
            ->modalWidth("md")
            ->action(function (array $data) {
                $kepengurusan = $this->record->kepengurusan()->firstOrCreate([
                    'mwcnu_id' => $this->record->id
                ], [
                    'mwcnu_id' => $this->record->id
                ]);

                $kepengurusan->ranting_nu()->create([
                    "posisi" => $data['posisi'],
                    "jabatan" => $data['jabatan'],
                    "jemaah_id" => $data['jemaah_id'],
                    "kepengurusan_id" => $kepengurusan->id,
                    "village_id" => $data['village_id'],
                    'masa_khidmat' => [
                        'start' => $data['start_khidmat'],
                        'end' => $data['end_khidmat'],
                    ],
                ]);



                $kepengurusan->save();

                $this->record->save();

                $this->mount();
            });
    }

    public function addAnakRanting(): Action
    {
        return Action::make("addAnakRanting")

            ->icon("heroicon-m-plus")
            ->label("Tambah Pengurus")
            ->form([
                Select::make("jemaah_id")
                    ->allowHtml()
                    ->required()
                    ->searchable()
                    ->options(function () {
                        $users = $this->record->jemaahs()
                            ->whereNotIn('id', $this->record->kepengurusan->all_pengurus()->pluck('id'))
                            ->get();

                        return $users->mapWithKeys(function ($user) {
                            return [$user->getKey() => static::getCleanOptionString($user)];
                        })->toArray();
                    })
                    ->getSearchResultsUsing(function (string $search) {
                        $users = $this->record->jemaahs()->where('nama_lengkap', 'like', "%{$search}%")->limit(50)->get();

                        return $users->mapWithKeys(function ($user) {
                            return [$user->getKey() => static::getCleanOptionString($user)];
                        })->toArray();
                    })
                    ->getOptionLabelUsing(function ($value): string {
                        $user = $this->record->jemaahs()->find($value);

                        return static::getCleanOptionString($user);
                    })
                    ->label("Warga"),
                Select::make("village_id")
                    ->options(function () {
                        $kecamatan = District::where("name", "like", "%{$this->record->nama_kecamatan}%")->first();

                        return $kecamatan->villages->pluck('name', 'id');
                    })
                    ->required()
                    ->native(false)
                    ->preload()
                    ->label("Desa"),
                Grid::make()
                    ->schema([
                        Select::make("posisi")
                            ->required()
                            ->options(Position::class)
                            ->default(Position::Syuriyah)
                            ->disableOptionWhen(fn(string $value): bool => $value === Position::Mustasyar->getLabel())
                            ->native(false)
                            ->preload()
                            ->label("Posisi"),
                        Select::make("jabatan")
                            ->live()
                            ->required()
                            ->options(function (Get $get) {
                                if ($get('posisi') == Position::Syuriyah) {
                                    return Position::Syuriyah->getJabatan($get('posisi'));
                                } elseif ($get('posisi') == Position::Tanfidziyah) {
                                    return Position::Tanfidziyah->getJabatan($get('posisi'));
                                }
                                return null;
                            })
                            ->preload()
                            ->native(false)
                            ->label("Jabatan"),
                    ])
                    ->columns(2),
                Grid::make()
                    ->schema([
                        TextInput::make("start_khidmat")
                            ->required()
                            ->placeholder("2020")
                            ->label("Tahun Mulai"),
                        TextInput::make("end_khidmat")
                            ->required()
                            ->placeholder("2022")
                            ->label("Tahun Selesai"),

                    ])->columns(2)

            ])
            ->modalWidth("md")
            ->action(function (array $data) {
                $kepengurusan = $this->record->kepengurusan()->firstOrCreate([
                    'mwcnu_id' => $this->record->id
                ], [
                    'mwcnu_id' => $this->record->id
                ]);

                $kepengurusan->anak_ranting()->create([
                    "posisi" => $data['posisi'],
                    "jabatan" => $data['jabatan'],
                    "jemaah_id" => $data['jemaah_id'],
                    "kepengurusan_id" => $kepengurusan->id,
                    "village_id" => $data['village_id'],
                    'masa_khidmat' => [
                        'start' => $data['start_khidmat'],
                        'end' => $data['end_khidmat'],
                    ],
                ]);



                $kepengurusan->save();

                $this->record->save();

                $this->mount();
            });
    }
}
