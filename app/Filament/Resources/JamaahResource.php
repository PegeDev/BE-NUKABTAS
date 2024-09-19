<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JamaahResource\Pages;
use App\Filament\Resources\JamaahResource\Pages\CreateJamaah;
use App\Filament\Resources\JamaahResource\Pages\DetailJamaah;
use App\Filament\Resources\JamaahResource\Pages\EditJamaah;
use Illuminate\Support\Str;
use App\Forms\Components\GridHeading;
use App\Models\Jemaah;
use App\Tables\Columns\ImageTextColumn;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;

class JamaahResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Jemaah::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = "Data Warga";

    protected static ?string $slug = "warga";


    public static function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make("Informasi Jamaah MWCNU")
                            ->schema([
                                Grid::make()
                                    ->schema([
                                        TextInput::make("nama_lengkap")
                                            ->placeholder("Nama Lengkap")
                                            ->required()
                                            ->label("Nama Lengkap"),
                                        TextInput::make("nama_panggilan")
                                            ->placeholder("Nama Panggilan")
                                            ->required()
                                            ->label("Nama Panggilan"),
                                    ])
                                    ->columns(2),
                                Grid::make()
                                    ->schema([
                                        TextInput::make("nik")
                                            ->placeholder("Nomor Induk Kependudukan")
                                            ->required()
                                            ->label("NIK/No. Identitas"),
                                        Select::make("status_pernikahan")
                                            ->placeholder("Pilih Status Pernikahan")
                                            ->options([
                                                "lajang" => "Lajang",
                                                "menikah" => "Menikah",
                                                "duda/janda" => "Duda / Janda",
                                            ])
                                            ->native(false)
                                            ->required()
                                            ->label("Status Pernikahan"),
                                    ])
                                    ->columns(2),
                                Grid::make()
                                    ->schema([
                                        TextInput::make("tempat_lahir")
                                            ->placeholder("Kab/Kota")
                                            ->required()
                                            ->label("Tempat Lahir"),
                                        DatePicker::make("tanggal_lahir")
                                            ->placeholder("YYYY-MM-DD")
                                            ->format('Y/m/d')
                                            ->required()
                                            ->label("Tanggal Lahir")
                                            ->native(false)

                                    ])
                                    ->columns(2),
                                Grid::make()
                                    ->schema([
                                        TextInput::make("email")
                                            ->required()
                                            ->placeholder("Email")
                                            ->required()
                                            ->label("Alamat Email"),
                                        TextInput::make("telp")
                                            ->required()
                                            ->tel()
                                            ->mask("9999 9999 99999")
                                            ->placeholder("Nomor Handphone")
                                            ->label("Nomor Handphone"),
                                    ])
                                    ->columns(2),
                                Grid::make()
                                    ->schema([
                                        Radio::make('jenis_kelamin')
                                            ->options([
                                                'laki-laki' => 'Laki-laki',
                                                'perempuan' => 'Perempuan',
                                            ])
                                            ->inline()
                                            ->extraAttributes([
                                                "class" => "justify-between py-1.5 px-2  border rounded-lg"
                                            ])
                                            ->required()
                                            ->inlineLabel(false),
                                        Select::make("pendidikan_terakhir")
                                            ->placeholder("Pilih pendidikan terakhir")
                                            ->options([
                                                "pondok pesantren" => "Pondok Pesantren",
                                                "sd" => "SD / Sederajat",
                                                "smp" => "SMP / Sederajat",
                                                "sma" => "SMA / Sederajat",
                                                "d3" => "D3",
                                                "s1" => "S1",
                                                "s2" => "S2",
                                                "s3" => "S3",
                                            ])
                                            ->required()
                                            ->label("Pendidikan Terakhir")
                                            ->native(false),
                                    ])
                                    ->columns(2),
                                Grid::make()
                                    ->schema([
                                        Group::make()
                                            ->schema([
                                                TextInput::make("pekerjaan")
                                                    ->required()
                                                    ->placeholder("Dosen, wiraswasta, dll.")
                                                    ->label("Pekerjaan"),
                                                Select::make("penghasilan")
                                                    ->placeholder("Pilih Penghasilan")
                                                    ->options([
                                                        "0" => "dibawah 1 Juta",
                                                        "1" => "1,1 Juta - 2,5 Juta",
                                                        "2" => "2,6 Juta - 5 Juta",
                                                        "3" => "5,1 Juta - 7,5 Juta",
                                                        "4" => "7,6 Juta - 10 Juta",
                                                        "5" => "10,1 Juta Keatas",

                                                    ])
                                                    ->required()
                                                    ->label("Penghasilan /bulan")
                                                    ->native(false),

                                            ])->columns(2)

                                    ])->columns(1),
                                GridHeading::make()
                                    ->schema([
                                        Group::make()
                                            ->relationship("alamat_jemaah")
                                            ->schema([
                                                Grid::make()
                                                    ->schema([
                                                        Select::make('provinsi')
                                                            ->options(function (Get $get) {
                                                                $province = Province::all()->pluck("name", "code")->toArray();
                                                                return $province;
                                                            })
                                                            ->afterStateHydrated(function (Select $component) {
                                                                return $component->state("32");
                                                            })
                                                            ->required()
                                                            ->extraAttributes(["readonly" => "readonly"])
                                                            ->default("32")
                                                            ->placeholder("Pilih Provinsi")
                                                            ->live(),
                                                        Select::make('kota')
                                                            ->options(function (Get $get) {
                                                                $findCity = City::where('province_code', $get("provinsi"))->pluck('name', 'code')->toArray();
                                                                return $findCity ?? [];
                                                            })
                                                            ->required()
                                                            ->placeholder("Pilih Kota")
                                                            ->preload()
                                                            ->searchable()
                                                            ->live()

                                                    ])
                                                    ->columns(2),
                                                Grid::make()
                                                    ->schema([
                                                        Select::make('kecamatan')
                                                            ->options(function (Get $get) {
                                                                $findKota = District::where('city_code', $get("kota"))->pluck('name', 'code')->toArray();
                                                                return  $findKota ?? [];
                                                            })
                                                            ->required()
                                                            ->placeholder("Pilih Kecamatan")
                                                            ->preload()
                                                            ->searchable()
                                                            ->live(),
                                                        Select::make('desa')
                                                            ->options(function (Get $get) {
                                                                $findDesa = Village::where('district_code', $get("kecamatan"))->pluck('name', 'code')->toArray();
                                                                return $findDesa ?? [];
                                                            })
                                                            ->required()
                                                            ->placeholder("Pilih Desa")
                                                            ->preload()
                                                            ->searchable()
                                                            ->live()

                                                    ])
                                                    ->columns(2),
                                            ]),
                                        Grid::make()
                                            ->relationship("detail_jemaah")
                                            ->schema([

                                                Textarea::make('alamat_detail')
                                                    ->required()
                                                    ->label("Alamat Lengkap")
                                                    ->placeholder("Nama Jalan, Gang, No. Rumah, RT dan RW")
                                                    ->rows(6)
                                                    ->cols(10)
                                                    ->columnSpanFull()
                                            ])
                                            ->columns(2)
                                    ])
                                    ->label("Alamat"),
                                GridHeading::make()
                                    ->schema([
                                        TextInput::make("kepengurusan")
                                            ->required()
                                            ->placeholder("")
                                            ->label("Kepengurusan di NU"),
                                        TextInput::make("jabatan_kepengurusan")
                                            ->required()
                                            ->placeholder("Ketua, Wakil Ketua, dll.")
                                            ->label("Jabatan Kepengurusan"),
                                    ])
                                    ->columns(2),
                                GridHeading::make()
                                    ->schema([
                                        Group::make()
                                            ->relationship("detail_jemaah")
                                            ->schema([
                                                Radio::make('isPesantren')
                                                    ->options([
                                                        'pernah' => 'Pernah',
                                                        'belum pernah' => 'Belum Pernah',
                                                    ])
                                                    ->label("Apakah pernah belajar dan mengaji di Pondok Pesantren?")
                                                    ->columnSpanFull()
                                                    ->required()
                                                    ->hidden(fn(Page $livewire) => $livewire instanceof DetailJamaah)
                                                    ->live(),
                                                Repeater::make('riwayat_pesantren')
                                                    ->label('Riwayat Pendidikan Pondok Pesantren')
                                                    ->reorderable(false)
                                                    ->schema([
                                                        TextInput::make('nama_pesantren')
                                                            ->label('Nama Pondok Pesantren')
                                                            ->required(),
                                                        TextInput::make('daerah')
                                                            ->label('Daerah')
                                                            ->required(),
                                                        Grid::make()
                                                            ->schema([
                                                                TextInput::make('tahun_masuk')
                                                                    ->label('Tahun Masuk')
                                                                    ->required(),
                                                                TextInput::make('tahun_lulus')
                                                                    ->label('Tahun Lulus'),
                                                            ])->columns(2)
                                                    ])
                                                    ->itemLabel(fn(array $state) => $state["nama_pesantren"] ?? null)
                                                    ->addAction(fn(FormAction $action) => $action->label("Tambah")->icon("heroicon-o-plus")->extraAttributes([
                                                        "class" => "ml-auto"
                                                    ]))
                                                    ->live()
                                                    ->collapsible()

                                                    ->columnSpanFull()
                                                    ->hidden(function (Get $get) {
                                                        if ($get("isPesantren") === "pernah") {
                                                            return false;
                                                        }
                                                        return true;
                                                    })
                                                    ->defaultItems(1),
                                            ])->columnSpanFull()

                                    ])
                                    ->columns(2),
                                GridHeading::make()
                                    ->schema([
                                        Group::make()
                                            ->relationship("detail_jemaah")
                                            ->schema([
                                                Repeater::make('riwayat_pendidikan')
                                                    ->label('Riwayat Pendidikan Formal')
                                                    ->reorderable(false)
                                                    ->schema([
                                                        TextInput::make('nama_sekolah')
                                                            ->label('Nama Sekolah / Perguruan Tinggi')
                                                            ->required(),
                                                        TextInput::make('jurusan')
                                                            ->label('Jurusan / Prodi'),
                                                        Grid::make()
                                                            ->schema([
                                                                TextInput::make('tahun_masuk')
                                                                    ->label('Tahun Masuk')
                                                                    ->required(),
                                                                TextInput::make('tahun_lulus')
                                                                    ->label('Tahun Lulus'),
                                                            ])->columns(2)
                                                    ])
                                                    ->itemLabel(fn(array $state) => $state["nama_sekolah"] ?? null)
                                                    ->addAction(fn(FormAction $action) => $action->label("Tambah")->icon("heroicon-o-plus")->extraAttributes([
                                                        "class" => "ml-auto"
                                                    ]))
                                                    ->live()
                                                    ->collapsed(true)
                                                    ->defaultItems(0)
                                                    ->collapsible()
                                                    ->columnSpanFull()
                                            ])->columnSpanFull()
                                    ])
                                    ->columns(2)

                            ])->columnSpanFull()
                    ])

                    ->columnSpan([
                        "default" => 12,
                        "md" => 8
                    ]),
                Grid::make()
                    ->schema([
                        Section::make("Foto Jamaah")
                            ->schema([
                                FileUpload::make('profile_picture')
                                    ->avatar()
                                    ->image()
                                    ->required()
                                    ->imageEditor()
                                    ->directory("images/profile")
                                    ->optimize("webp")
                                    ->maxSize(2048)
                                    ->hiddenLabel(true)
                                    ->extraAttributes([
                                        "class" => "w-full flex items-center justify-center"
                                    ])
                            ])->columns(1),
                        Section::make("Foto KTP")
                            ->relationship("detail_jemaah")
                            ->schema([
                                FileUpload::make('foto_ktp')
                                    ->image()
                                    ->required()
                                    ->imageEditor()
                                    ->directory("images/ktp")
                                    ->optimize("webp")
                                    ->maxSize(2048)
                                    ->hiddenLabel(true)
                                    ->extraAttributes([
                                        "class" => "w-full flex justify-center"
                                    ])
                            ])->columns(1),
                        Section::make("Riwayat Organisasi")
                            ->schema([
                                Repeater::make('riwayat_organisasi')
                                    ->label('Pengalaman Organisasi Internal NU')
                                    ->schema([
                                        TextInput::make('nama_organisasi')
                                            ->label('Nama Organisasi')
                                            ->required(),
                                        TextInput::make('jabatan')
                                            ->label('Jabatan')
                                            ->required(),
                                        Grid::make()
                                            ->schema([
                                                Checkbox::make('isActiveAnggota')
                                                    ->label("Masih Menjadi Anggota")
                                                    ->inline()
                                                    ->columnSpanFull()
                                                    ->live(),
                                                TextInput::make('tahun_mulai')
                                                    ->label('Tahun Mulai')
                                                    ->required(),
                                                TextInput::make('tahun_selesai')
                                                    ->label('Tahun Selesai')
                                                    ->disabled(function (Get $get) {
                                                        if ($get("isActiveAnggota")) {
                                                            return true;
                                                        }
                                                        return false;
                                                    }),
                                            ])->columns(2)
                                    ])
                                    ->itemLabel(fn(array $state): ?string => $state['nama_organisasi'] ?? null)
                                    ->collapsible()
                                    ->collapsed(true)
                                    ->reorderable(false)
                                    ->live()
                                    ->columnSpanFull()
                                    ->defaultItems(0)
                                    ->addAction(function (FormAction $action) {
                                        return $action
                                            ->label("Tambah")
                                            ->extraAttributes([
                                                "class" => "ml-auto"
                                            ])
                                            ->icon("heroicon-o-plus");
                                    }),

                                GridHeading::make(),
                                Repeater::make('riwayat_organisasi_external')
                                    ->label('Pengalaman Organisasi External')
                                    ->schema([
                                        TextInput::make('nama_organisasi')
                                            ->label('Nama Organisasi')
                                            ->required(),
                                        TextInput::make('jabatan')
                                            ->label('Jabatan')
                                            ->required(),
                                        Grid::make()
                                            ->schema([
                                                TextInput::make('tahun_mulai')
                                                    ->label('Tahun Mulai')
                                                    ->required(),
                                                TextInput::make('tahun_selesai')
                                                    ->label('Tahun Selesai'),
                                            ])->columns(2)
                                    ])
                                    ->collapsible()
                                    ->collapsed(true)
                                    ->live()
                                    ->reorderable(false)
                                    ->itemLabel(fn(array $state): ?string => $state['nama_organisasi'] ?? null)
                                    ->columnSpanFull()
                                    ->defaultItems(0)
                                    ->addAction(function (FormAction $action) {
                                        return $action
                                            ->label("Tambah")
                                            ->extraAttributes([
                                                "class" => "ml-auto"
                                            ])
                                            ->icon("heroicon-o-plus");
                                    })

                            ])->columns(1),
                        Section::make("Riwayat Kaderisasi")
                            ->schema([
                                Repeater::make('riwayat_kaderisasi')
                                    ->label('Kaderisasi Lainnya')
                                    ->simple(
                                        TextInput::make('nama_organisasi')
                                            ->required(),

                                    )
                                    ->live()
                                    ->reorderable(false)
                                    ->columnSpanFull()
                                    ->defaultItems(1)
                                    ->addAction(function (FormAction $action) {
                                        return $action
                                            ->label("Tambah")
                                            ->extraAttributes([
                                                "class" => "ml-auto"
                                            ])
                                            ->icon("heroicon-o-plus");
                                    })
                            ]),


                    ])
                    ->columnSpan([
                        "default" => 12,
                        "md" => 4
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageTextColumn::make("nama_lengkap")
                    ->state(fn($record) => $record)
                    ->label("NAMA LENGKAP/NIK")
                    ->view("tables.columns.image-text-column"),
                TextColumn::make('jenis_kelamin')
                    ->description(fn(Jemaah $record): string => (Carbon::parse($record->tanggal_lahir)->age ?? "") . " Tahun")
                    ->weight(FontWeight::SemiBold)
                    ->formatStateUsing(fn($state) => Str::title($state))
                    ->label("GENDER/UMUR")
                    ->size(TextColumnSize::Small),
                TextColumn::make('telp')
                    ->formatStateUsing(fn($state) => "+{$state}")
                    ->description(fn(Jemaah $record): string => $record->email)
                    ->weight(FontWeight::SemiBold)
                    ->label("TELEPON/EMAIL")
                    ->size(TextColumnSize::Small),
                TextColumn::make('alamat_jemaah')
                    ->formatStateUsing(function ($state) {
                        return Str::title($state->provinsi()->first()->name);
                    })
                    ->description(function ($state) {
                        $findCity =  $state ? $state->kota()->first()->name : null;
                        $findDistrict =   $state ? $state->kecamatan()->first()->name : null;
                        return Str::title($findDistrict  . ", " .  $findCity);
                    })
                    ->placeholder("-")
                    ->weight(FontWeight::SemiBold)
                    ->label("ALAMAT")
                    ->size(TextColumnSize::Small),
                TextColumn::make('kepengurusan_type')
                    ->formatStateUsing(function ($state) {
                        return Str::title($state->type);
                    })
                    ->badge()
                    ->weight(FontWeight::SemiBold)
                    ->label("KEPENGURUSAN")
                    ->size(TextColumnSize::Small),
                TextColumn::make("pekerjaan")
                    ->formatStateUsing(function ($state) {

                        return Str::title($state);
                    })
                    ->placeholder("-")
                    ->label("PEKERJAAN")
            ])
            ->recordUrl(fn(Jemaah $record): string => self::getUrl('detail', ['record' => $record->id]))
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Action::make("lihat_detail")
                        ->label("Lihat detail")
                        ->icon("heroicon-o-eye")
                        ->url(fn(Jemaah $record): string => self::getUrl('detail', ['record' => $record->id])),
                    DeleteAction::make("hapus")
                        ->label("Hapus")
                        ->icon("heroicon-o-trash")
                        ->color("danger")
                        ->visible(auth()->user()->can("delete_jamaah"))
                ])
                    ->label("Action")
            ])
            ->striped()
            ->headerActions([])
            ->groupedBulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
        ];
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJamaahs::route('/'),
            'edit' => Pages\EditJamaah::route('/{record}/edit'),
            "detail" => Pages\DetailWarga::route('/{record}'),
        ];
    }
}
