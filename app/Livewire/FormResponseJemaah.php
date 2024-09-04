<?php

namespace App\Livewire;

use App\Forms\Components\GridHeading;
use App\Models\DetailJemaah;
use App\Models\FormMwcnu;
use App\Models\Jemaah;
use App\Models\Mwcnu;
use Exception;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
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
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;
use Livewire\Attributes\Title;
use Livewire\Component;

class FormResponseJemaah extends Component implements HasForms
{

    use InteractsWithForms;



    public $mwcnu;

    public $code;

    public ?array $data = [];


    public function mount($code)
    {
        $find =  FormMwcnu::where('code', $code)->first();
        $mwcnu = Mwcnu::where("id", $find->mwcnu_id)->first();

        if (!$find || !$find->is_enabled || !$mwcnu->detail_mwcnus()->exists()) {
            abort(403);
        } else {
            $this->mwcnu = $mwcnu;
        }
        $this->form->fill();
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
                "mwcnu_id" => $this->mwcnu->id,
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
                ->title('Berhasil mendaftar')
                ->success()
                ->send();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Notification::make()
                ->title('Terjadi kesalahan saat mendaftar')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("Data Personal")
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextInput::make("nama_lengkap")
                                    ->required()
                                    ->placeholder("Nama Lengkap")
                                    ->minLength(2)
                                    ->maxLength(255),
                                TextInput::make("nama_panggilan")
                                    ->placeholder("Nama Panggilan")
                                    ->minLength(2)
                                    ->maxLength(255),
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
                                    ->required()
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
                                        "class" => "justify-between py-1.5 px-4 border rounded-lg"
                                    ])
                                    ->inlineLabel(false),
                                TextInput::make("pekerjaan")
                                    ->placeholder("Dosen, wiraswasta, dll.")
                                    ->label("Pekerjaan"),
                            ])
                            ->columns(2),
                        GridHeading::make()
                            ->schema([
                                Grid::make()
                                    ->schema([
                                        Select::make('provinsi')
                                            ->options(function () {
                                                $province = Province::all()->pluck("name", "code")->toArray();
                                                return $province;
                                            })
                                            ->default("32")
                                            ->disabled()
                                            ->required()
                                            ->placeholder("Pilih Provinsi")
                                            ->preload()
                                            ->searchable()
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
                                            ->live(),
                                    ])
                                    ->columns(2),
                                Grid::make()
                                    ->schema([
                                        Textarea::make('alamat_detail')
                                            ->label("Alamat Lengkap")
                                            ->placeholder("Nama Jalan, Gang, No. Rumah, RT dan RW")
                                            ->rows(6)
                                            ->cols(10)
                                            ->autosize(false)
                                            ->columnSpanFull()
                                    ])
                                    ->columns(2)
                            ])
                            ->label("Alamat"),
                        // GridHeading::make()
                        //     ->schema([
                        //         TextInput::make("kepengurusan")
                        //             ->placeholder("")
                        //             ->label("Kepengurusan di NU"),
                        //         TextInput::make("jabatan_kepengurusan")
                        //             ->placeholder("Ketua, Wakil Ketua, dll.")
                        //             ->label("Jabatan Kepengurusan"),
                        //     ])
                        //     ->columns(2),
                        GridHeading::make()
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
                                    ->required()
                                    ->label("Pendidikan Terakhir")
                                    ->native(false),
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
                                    ->native(false)
                            ])
                            ->columns(2),
                        GridHeading::make()
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
                        GridHeading::make()
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
                            ->columns(2),
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
                            ->columns(2),
                        GridHeading::make()
                            ->schema([
                                FileUpload::make('profile_picture')
                                    ->extraAttributes([
                                        "class" => "w-full flex justify-center"
                                    ])
                                    ->directory("images/profile")
                                    ->imageEditor()
                                    ->maxSize(2048)
                                    ->label("Foto Profil"),
                                FileUpload::make('foto_ktp')
                                    ->extraAttributes([
                                        "class" => "w-full flex justify-center"
                                    ])
                                    ->directory("images/ktp")
                                    ->imageEditor()
                                    ->required()
                                    ->maxSize(2048)
                                    ->label("Foto KTP")
                            ])
                            ->columns(1),
                    ]),
                Section::make()
                    ->schema([
                        CheckboxList::make('accept')
                            ->options([
                                '1' => 'Saya setuju dan sudah mengisi data pendaftaran jamaah',
                                '2' => 'Saya bersedia membayar infaq yang ditentukan oleh panitia',
                            ])
                            ->required()
                            ->live()
                            ->label("Dengan ini saya menyatakan:"),
                        Actions::make([
                            Action::make("submit")
                                ->label("Daftar")
                                ->submit(true)
                                ->disabled(function (Get $get) {
                                    if (in_array("1", $get("accept")) && in_array("2", $get("accept"))) {
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
            ])->statePath('data');
    }


    public function render()
    {
        return view('livewire.form-response-jemaah')->title("Formulir Pendaftaran {$this->mwcnu->nama_kecamatan} | " . config("app.name"));
    }
}
