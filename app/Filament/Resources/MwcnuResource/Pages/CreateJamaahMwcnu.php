<?php

namespace App\Filament\Resources\MwcnuResource\Pages;

use App\Filament\Resources\JamaahResource;
use App\Filament\Resources\MwcnuResource;
use App\Forms\Components\GridHeading;
use App\Models\DetailJemaah;
use App\Models\Jemaah;
use Exception;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;
use PhpParser\Node\Stmt\TryCatch;

class CreateJamaahMwcnu extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = MwcnuResource::class;

    protected static ?string $title = 'Buat Jamaah baru';

    protected static string $view = 'filament.resources.mwcnu-resource.pages.create-jamaah';

    public ?array $data = [];

    public $record;


    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
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
                                            ->placeholder("Email")
                                            ->required()
                                            ->label("Alamat Email"),
                                        TextInput::make("telp")
                                            ->prefix("+62")
                                            ->tel()
                                            ->mask("999 9999 99999")
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
                                            ->inlineLabel(false),
                                        TextInput::make("pekerjaan")
                                            ->placeholder("Dosen, wiraswasta, dll.")
                                            ->label("Pekerjaan"),
                                    ])
                                    ->columns(2),
                                View::make("forms.components.grid-heading")
                                    ->schema([
                                        Grid::make()
                                            ->schema([
                                                Select::make('provinsi')
                                                    ->options(function (Get $get) {
                                                        $province = Province::all()->pluck("name", "code")->toArray();
                                                        return $province;
                                                    })
                                                    ->placeholder("Pilih Provinsi")
                                                    ->preload()
                                                    ->searchable()
                                                    ->live(),
                                                Select::make('kota')
                                                    ->options(function (Get $get) {
                                                        $findCity = City::where('province_code', $get("provinsi"))->pluck('name', 'code')->toArray();
                                                        return $findCity ?? [];
                                                    })
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
                                                    ->placeholder("Pilih Kecamatan")
                                                    ->preload()
                                                    ->searchable()
                                                    ->live(),
                                                Select::make('desa')
                                                    ->options(function (Get $get) {
                                                        $findDesa = Village::where('district_code', $get("kecamatan"))->pluck('name', 'code')->toArray();
                                                        return $findDesa ?? [];
                                                    })
                                                    ->placeholder("Pilih Desa")
                                                    ->preload()
                                                    ->searchable()
                                                    ->live(),
                                            ])
                                            ->columns(2),
                                        Grid::make()
                                            ->schema([
                                                Textarea::make('alamat_detail')
                                                    ->label("Alamat Lengkap")
                                                    ->rows(6)
                                                    ->cols(10)
                                                    ->columnSpanFull()
                                            ])
                                            ->columns(2)
                                    ])
                                    ->label("Alamat"),
                                View::make("forms.components.grid-heading")
                                    ->schema([
                                        TextInput::make("kepengurusan")
                                            ->placeholder("")
                                            ->label("Kepengurusan di NU"),
                                        TextInput::make("jabatan_kepengurusan")
                                            ->placeholder("Ketua, Wakil Ketua, dll.")
                                            ->label("Jabatan Kepengurusan"),
                                    ])
                                    ->columns(2),
                                View::make("forms.components.grid-heading")
                                    ->schema([
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
                                            ->label("Pendidikan Terakhir")
                                            ->native(false)
                                            ->columnSpanFull(),

                                    ])
                                    ->columns(2),
                                View::make("forms.components.grid-heading")
                                    ->schema([
                                        Radio::make('isPesantren')
                                            ->options([
                                                'pernah' => 'Pernah',
                                                'belum pernah' => 'Belum Pernah',
                                            ])
                                            ->label("Apakah pernah belajar dan mengaji di Pondok Pesantren?")
                                            ->columnSpanFull()
                                            ->required()
                                            ->live(),
                                        Repeater::make('riwayat_pesantren')
                                            ->label('Riwayat Pendidikan Pondok Pesantren')
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
                                            ->addAction(fn(Action $action) => $action->label("Tambah")->icon("heroicon-o-plus")->extraAttributes([
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

                                    ])
                                    ->columns(2),
                                View::make("forms.components.grid-heading")
                                    ->schema([
                                        Repeater::make('riwayat_pendidikan')
                                            ->label('Riwayat Pendidikan Formal')
                                            ->schema([
                                                TextInput::make('nama_sekolah')
                                                    ->label('Nama Sekolah / Perguruan Tinggi')
                                                    ->required(),
                                                TextInput::make('jurusan')
                                                    ->label('Jurusan / Prodi')
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
                                            ->itemLabel(fn(array $state) => $state["nama_sekolah"] ?? null)
                                            ->addAction(fn(Action $action) => $action->label("Tambah")->icon("heroicon-o-plus")->extraAttributes([
                                                "class" => "ml-auto"
                                            ]))
                                            ->live()
                                            ->collapsed(true)
                                            ->defaultItems(0)
                                            ->collapsible()
                                            ->columnSpanFull()
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
                                    ->hiddenLabel(true)
                                    ->extraAttributes([
                                        "class" => "w-ful flex justify-center"
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
                                    ->addAction(function (Action $action) {
                                        return $action
                                            ->label("Tambah")
                                            ->extraAttributes([
                                                "class" => "ml-auto"
                                            ])
                                            ->icon("heroicon-o-plus");
                                    }),

                                View::make("forms.components.grid-heading"),
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
                                    ->addAction(function (Action $action) {
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
                                    ->addAction(function (Action $action) {
                                        return $action
                                            ->label("Tambah")
                                            ->extraAttributes([
                                                "class" => "ml-auto"
                                            ])
                                            ->icon("heroicon-o-plus");
                                    })
                            ]),
                        Actions::make([
                            Action::make("submit")
                                ->label("Buat Jamaah")
                                ->submit(true)
                                ->size(ActionSize::ExtraLarge)
                                ->extraAttributes([
                                    "class" => "w-full"
                                ])


                        ])->columnSpanFull()

                    ])
                    ->columnSpan([
                        "default" => 12,
                        "md" => 4
                    ])

            ])
            ->statePath("data");
    }

    public function create(): void
    {
        try {
            $state = $this->form->getState();

            $state["telp"] =  "62" . str_replace(" ", "", preg_replace("/0/", "", $state["telp"]));

            $formatedJemaah = [
                "nama_lengkap" => $state["nama_lengkap"],
                "nama_panggilan" => $state["nama_panggilan"],
                "nik" => $state["nik"],
                "telp" => $state["telp"],
                "email" => $state["email"],
                "tempat_lahir" => $state["tempat_lahir"],
                "tanggal_lahir" => $state["tanggal_lahir"],
                "jenis_kelamin" => $state["jenis_kelamin"],
                "profile_picture" => $state["profile_picture"] ?? "",
                "kepengurusan" => $state["kepengurusan"] ?? "",
                "jabatan_kepengurusan" => $state["jabatan_kepengurusan"] ?? "",
                "alamat_lengkap" => json_encode([
                    "provinsi" => $state["provinsi"],
                    "kota" => $state["kota"],
                    "kecamatan" => $state["kecamatan"],
                    "desa" => $state["desa"],
                ]),
                "mwcnu_id" => $this->record,
            ];


            $jemaah = Jemaah::create($formatedJemaah);

            $formatedDetailJemaah = [
                "penghasilan" => $state["penghasilan"] ?? "0",
                "pekerjaan" => $state["pekerjaan"],
                "alamat_detail" => $state["alamat_detail"],
                "pendidikan_terakhir" => $state["pendidikan_terakhir"],
                "riwayat_pendidikan" => $state["riwayat_pendidikan"],
                "riwayat_pesantren" => $state["riwayat_pesantren"] ?? [],
                "riwayat_organisasi" => $state["riwayat_organisasi"],
                "riwayat_organisasi_external" => $state["riwayat_organisasi_external"],
                "status_pernikahan" => $state["status_pernikahan"],
                "jemaah_id" => $jemaah->id,
            ];

            $detailJemaah = new DetailJemaah($formatedDetailJemaah);
            $jemaah->detail()->save($detailJemaah);

            $this->form->model($jemaah)->saveRelationships();

            Notification::make()
                ->title('Jemaah berhasil ditambahkan')
                ->success()
                ->send();

            Redirect::route("filament.dashboard.resources.data-kecamatan.jamaah", ["record" => $this->record]);
        } catch (Exception $e) {
            Notification::make()
                ->title('Terjadi kesalahan saat menambahkan Jemaah')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }
}
