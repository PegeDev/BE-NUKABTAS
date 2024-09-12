<?php

namespace App\Livewire\MwcnuResource;

use App\Forms\Components\GridHeading;
use App\Models\DetailJemaah;
use App\Models\FormMwcnu;
use App\Models\Jemaah;
use App\Models\Mwcnu;
use Exception;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
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
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Support\Enums\ActionSize;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;
use Livewire\Attributes\Title;
use Livewire\Component;

class FormResponseJemaah extends Component implements HasForms
{

    use InteractsWithForms;

    public Mwcnu $mwcnu;

    public $code;

    public $isSubmited = false;

    public $isFormOpen = false;

    public ?array $data = [];

    public function mount($code)
    {
        $find =  FormMwcnu::where('code', $code)->first();
        $mwcnu = Mwcnu::where("id", $find->mwcnu_id)->first();
        if (!$mwcnu->detail_mwcnus()->exists()) {
            abort(404);
        } else {
            $this->mwcnu = $mwcnu;
        }
        if (!$find || !$find->is_enabled) {
            $this->isFormOpen = false;
        } else {
            $this->isFormOpen = true;
        }
        $this->form->fill();
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $jemaah = $this->mwcnu->jemaahs()->create($data);

        $jemaah->alamat_jemaah()->updateOrCreate([
            "provinsi" => $data["provinsi"] ?? "32",
            "kota" => $data["kota"] ?? "",
            "kecamatan" => $data["kecamatan"] ?? "",
            "desa" => $data["desa"] ?? "",
            "jemaah_id" => $jemaah->id
        ]);

        $formatedDetailJemaah = [
            "alamat_detail" => $data["alamat_detail"],
            "riwayat_pendidikan" => $data["riwayat_pendidikan"],
            "riwayat_pesantren" => $data["riwayat_pesantren"] ?? null,
            "riwayat_kaderisasi" => $data["riwayat_kaderisasi"] ?? null,
            "foto_ktp" => $data["foto_ktp"] ?? "",
            "jemaah_id" => $jemaah->id,
        ];

        $detailJemaah = new DetailJemaah($formatedDetailJemaah);

        $jemaah->detail_jemaah()->save($detailJemaah);

        $this->form->model($jemaah)->saveRelationships();

        $this->isSubmited = true;
    }

    protected function getMessages(): array
    {
        return [
            'data.status_pernikahan.required' => __('Pilih salah satu Status Pernikahan'),
            'data.nik.required' => __('NIK harus diisi!'),
            'data.nik.max_digits' => __('NIK terlalu panjang, Maksimal 16 angka!'),
            'data.tanggal_lahir.required' => __('Tanggal Lahir harus diisi'),
            'data.provinsi.required' => __('Pilih salah satu Provinsi'),
            'data.kota.required' => __('Pilih salah satu Kota/Kab'),
            'data.kecamatan.required' => __('Pilih salah satu Kecamatan'),
            'data.desa.required' => __('Pilih salah satu Desa'),
            'data.isPesantren.required' => __('Pilih salah satu Pernah / Belum Pernah'),
            'data.profile_picture.required' => __('Masukan Foto Warga!'),
            'data.foto_ktp.required' => __('Masukan Foto KTP!'),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make("Detail Personal")
                            ->schema([
                                Grid::make()
                                    ->schema([
                                        TextInput::make("nama_lengkap")
                                            ->placeholder("Nama Lengkap")
                                            ->required()
                                            ->live()
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
                                            ->mask("9999999999999999")
                                            ->maxLength(16)
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
                                            ->native(true)

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
                                            ->dehydrateStateUsing(fn($state) =>  "62" . str_replace(" ", "", preg_replace("/0/", "", $state)))
                                            ->required()
                                            ->tel()
                                            ->mask("9999 9999 99999")
                                            ->placeholder("Nomor Telpon")
                                            ->label("Nomor Telpon"),
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
                                                            ->disabled()
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
                                                    ->defaultItems(0),
                                            ])->columnSpanFull()

                                    ])
                                    ->columns(2),
                                GridHeading::make()
                                    ->schema([
                                        Group::make()
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
                                                    ->addAction(fn(Action $action) => $action->label("Tambah")->icon("heroicon-o-plus")->extraAttributes([
                                                        "class" => "ml-auto"
                                                    ]))
                                                    ->live()
                                                    ->collapsed(true)
                                                    ->defaultItems(0)
                                                    ->collapsible()
                                                    ->columnSpanFull()
                                            ])->columnSpanFull()

                                    ])
                                    ->columns(2),
                                GridHeading::make()
                                    ->schema([
                                        FileUpload::make('profile_picture')
                                            ->image()
                                            ->required()
                                            ->imageEditor()
                                            ->directory("images/profile")
                                            ->optimize("webp")
                                            ->maxSize(2048)
                                            ->label("Foto Profil")
                                            ->extraAttributes([
                                                "class" => "w-full flex items-center justify-center"
                                            ])
                                    ]),
                                GridHeading::make()
                                    ->schema([
                                        FileUpload::make('foto_ktp')
                                            ->image()
                                            ->required()
                                            ->imageEditor()
                                            ->directory("images/ktp")
                                            ->optimize("webp")
                                            ->maxSize(2048)
                                            ->label("Foto KTP")
                                            ->extraAttributes([
                                                "class" => "w-full flex justify-center"
                                            ])
                                    ]),
                                GridHeading::make()
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
                                            ->defaultItems(0)
                                            ->addAction(function (Action $action) {
                                                return $action
                                                    ->label("Tambah")
                                                    ->extraAttributes([
                                                        "class" => "ml-auto"
                                                    ])
                                                    ->icon("heroicon-o-plus");
                                            })
                                    ])

                            ])->columnSpanFull(),
                        Section::make()
                            ->schema([
                                CheckboxList::make('accept')
                                    ->options([
                                        '1' => 'Saya setuju dan sudah mengisi data pendaftaran jamaah',
                                    ])
                                    ->required()
                                    ->live()
                                    ->label("Dengan ini saya menyatakan:"),
                                Actions::make([
                                    Action::make("create")
                                        ->label("Daftar")
                                        ->submit(true)
                                        ->disabled(function (Get $get) {
                                            if (in_array("1", $get("accept"))) {
                                                return false;
                                            } else {
                                                return true;
                                            }
                                        })
                                        ->color("primary")
                                        ->size(ActionSize::ExtraLarge)
                                        ->extraAttributes([
                                            "class" => "w-full bg-primary"
                                        ])
                                ])->columnSpanFull()
                            ])
                    ]),


            ])->statePath('data');
    }

    // #[Title('Pendaftaran Jamaah')]

    public function render()
    {
        return view('livewire.form-response-jemaah')
            ->title("Pendaftaran Jema'ah MWCNU " . $this->mwcnu->nama_kecamatan . " | " . config("app.name"));
    }
}
