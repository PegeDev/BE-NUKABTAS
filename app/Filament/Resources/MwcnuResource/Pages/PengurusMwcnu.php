<?php

namespace App\Filament\Resources\MwcnuResource\Pages;

use App\Enums\Position;
use App\Filament\Components\UseLembagaModalForm;
use App\Filament\Components\UsePengurusMwcModalForm;
use App\Filament\Components\UseRantingModalForm;
use App\Filament\Resources\JamaahResource;
use App\Filament\Resources\MwcnuResource;
use App\Models\BanomMaster;
use App\Models\BanomMwcnu;
use App\Models\LembagaMaster;
use App\Models\Mwcnu;
use App\Tables\Columns\ImageTextColumn;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Mockery\Matcher\Not;

class PengurusMwcnu extends Page implements HasForms, HasTable, HasActions
{
    use InteractsWithActions;
    use InteractsWithTable;

    protected static string $resource = MwcnuResource::class;

    protected static string $view = 'filament.resources.mwcnu-resource.pages.pengurus-mwcnu';

    public $activeTab = "semua";

    public Mwcnu $record;

    public $pengurusCount = 0, $allCount = 0, $banomCount = 0, $lembagaCount = 0, $rantingNuCount = 0, $anakRantingCount = 0;

    public bool $isTabAll = false;

    protected $queryTable = null;

    public function mount(): void
    {
        $this->pengurusCount = $this->record->pengurus_mwcnu()->count();

        $this->lembagaCount = $this->record->lembaga_mwcnu()->count();

        $this->banomCount = $this->record->banom_mwcnu()->count();

        $this->rantingNuCount = $this->record->ranting_nu()->count();

        $this->anakRantingCount = $this->record->anak_ranting()->count();

        $this->allCount = $this->record->all_pengurus()->count();

        $this->isTabAll = $this->activeTab === 'semua';
    }



    public function setTabs(string $activeTab): void
    {
        $this->activeTab = $activeTab;
        $this->isTabAll = $activeTab === 'semua';

        $this->resetTable();

        // dd($this->record->pengurus_sk);
    }

    public function createAction(): ?Action
    {
        return match ($this->activeTab) {
            'mwc' =>  $this->addPengurus(),
            'lembaga' =>  $this->addLembaga(),
            'banom' =>  $this->addBanom(),
            'ranting_nu' =>   $this->addRantingNu(),
            'anak_ranting' =>  $this->addAnakRanting(),
            default => null,
        };
    }

    public function getSuratKeputusan()
    {
        return match ($this->activeTab) {
            'mwc' => $this->record->pengurus_sk->sk_pengurus,
            'lembaga' =>  $this->record->pengurus_sk->sk_lembaga,
            'banom' =>   $this->record->pengurus_sk->sk_banom,
            'ranting_nu' =>  $this->record->pengurus_sk->sk_ranting_nu,
            'anak_ranting' =>  $this->record->pengurus_sk->sk_anak_ranting,
            default => null,
        };
    }

    public function formSchema()
    {
        return match ($this->activeTab) {
            'mwc' => UsePengurusMwcModalForm::schema($this->record, true),
            'ranting_nu' => UseRantingModalForm::schema($this->record, true),
            'anak_ranting' => UseRantingModalForm::schema($this->record, true),
            'lembaga' => UseLembagaModalForm::schema($this->record, LembagaMaster::class, "lembaga_id", true),
            'banom' => UseLembagaModalForm::schema($this->record, BanomMaster::class, "banom_id", true),
        };
    }

    public function table(Table $table): Table
    {

        $relation = match ($this->activeTab) {
            'mwc' => 'pengurus_mwc',
            'lembaga' => 'lembaga',
            'banom' => 'banom',
            'ranting_nu' => 'ranting_nu',
            'anak_ranting' => 'anak_ranting',
            default => null,
        };

        return $table
            ->query(fn() => $this->record->all_pengurus($relation))
            ->emptyStateIcon("fluentui-document-dismiss-20-o")
            ->emptyStateActions([
                ActionsAction::make("upload_sk")
                    ->url(fn() => (MwcnuResource::getUrl("surat-keputusan", ["record" => $this->record])))
                    ->icon("fluentui-document-add-16-o")
                    ->visible(fn() => !$this->getSuratKeputusan() && !$this->isTabAll)
                    ->openUrlInNewTab()
                    ->label("Upload Surat Keputusan")
            ])
            ->recordUrl(fn($record) => JamaahResource::getUrl("detail", ["record" => $record->jemaah->id]))
            ->emptyStateHeading(fn() => $this->getSuratKeputusan() || $this->isTabAll ? "Data pengurus tidak ditemukan" : "Surat Keputusan tidak ditemukan")
            ->striped()
            ->heading(fn() => "Data Pengurus " . Str::upper(Str::replace("_", " ", $this->activeTab)))
            ->searchPlaceholder("NAMA / NIK")
            ->columns([
                ImageTextColumn::make("jemaah.nik")
                    ->state(fn($record) => $record)
                    ->searchable("jemaahs.nik")
                    ->label("NAMA LENGKAP/NIK"),
                TextColumn::make('jemaah.jenis_kelamin')
                    ->description(fn($record) => Carbon::parse($record->tanggal_lahir)->age . " tahun")
                    ->weight(FontWeight::Medium)
                    ->formatStateUsing(fn($state) => strtoupper($state))
                    ->label("GENDER/UMUR")
                    ->size(TextColumnSize::Small),
                TextColumn::make('jemaah.telp')
                    ->description(function ($record) {
                        if ($record->jemaah) {
                            return $record->email;
                        } else {
                            return $record->email;
                        }
                    })
                    ->formatStateUsing(fn($state) => "+" . str_replace(array('+', ',', '(', ')', ' '), '', $state))
                    ->weight(FontWeight::Medium)
                    ->label("TELEPON/EMAIL")
                    ->size(TextColumnSize::Small),
                TextColumn::make('jemaah.alamat_jemaah')
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
                TextColumn::make('jemaah.kepengurusan_type.nama_desa')
                    ->label("DESA")
                    ->visible(fn() => $this->activeTab == 'anak_ranting' || $this->activeTab == "ranting_nu")
                    ->badge()
                    ->extraAttributes(["class" => 'truncate'])
                    ->placeholder("-"),
                TextColumn::make('jemaah.kepengurusan_type')
                    ->formatStateUsing(fn($state) => Str::upper($state->type))
                    ->description(function ($state) {
                        $jabatan = $state?->jabatan ? preg_replace("/_/", " ", $state->jabatan) : null;
                        $posisi = $state?->posisi ?? null;
                        $separator = isset($posisi) && $jabatan ? ', ' : null;

                        return Str::title($posisi . $separator . $jabatan);
                    })
                    ->label("KEPENGURUSAN")
                    ->color(fn($state) => match (Str::slug($state->type)) {
                        "pengurus-mwc" => Color::Fuchsia,
                        "ranting-nu" => Color::Cyan,
                        "anak-ranting" => Color::Blue,
                        "banom" => "warning",
                        "lembaga" => "danger",
                        default => "gray"
                    })
                    ->badge()
                    ->placeholder("-"),
                // TextColumn::make('end_khidmat')
                //     ->formatStateUsing(function (SuratKeputusanMwcnu $record, $state) {
                //         dd($state);
                //         return new HtmlString(
                //             Blade::render(
                //                 Carbon::parse($record->start_khidmat)->translatedFormat("d M Y") . " - " . Carbon::parse($record->end_khidmat)->translatedFormat("d M Y") . '<x-filament::badge size="sm" color="{{ $color }}">{{ $status }}</x-filament::badge>',
                //                 ["status" => Carbon::now()->gt(Carbon::parse($state)) ? 'SELESAI' : 'BERJALAN', "color" => Carbon::now()->gt(Carbon::parse($state)) ? 'danger' : 'info']
                //             )
                //         );
                //     })
                //     ->label("MASA KHIDMAT"),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make("editPengurus")
                        ->form(function ($record) {
                            $this->formSchema();
                        })
                        ->modalHeading("Edit Pengurus")
                        ->label("Edit Pengurus")
                        ->modalWidth("md")
                        ->visible(fn($record) => ! $this->isTabAll && (auth()->user()->hasRole(['super_admin', 'admin_kabupaten']) || $record->admin_id == auth()->id())),
                    DeleteAction::make("deletePengurus")
                        ->modalHeading('Hapus Pengurus')
                        ->modalDescription("Apa kamu yakin ingin menghapus pengurus ini? \nData yang di hapus tidak bisa dikembalikan")
                        ->modalSubmitActionLabel('Hapus')
                        ->visible(fn($record) => !$this->isTabAll && (auth()->user()->hasRole(['super_admin', 'admin_kabupaten']) || $record->admin_id == auth()->id()))
                        ->label("Hapus Pengurus")
                        ->successNotificationTitle("Pengurus berhasil dihapus")
                        ->after(function ($record) {
                            $this->mount();
                        })
                ]),
            ]);
    }





    public function addPengurus(): Action
    {
        return Action::make("addPengurus")
            ->icon("heroicon-m-plus")
            ->disabled(fn() => !$this->getSuratKeputusan())
            ->successNotificationTitle("Berhasil menambah Pengurus MWC")
            ->label("Tambah Pengurus")
            ->form(
                UsePengurusMwcModalForm::schema($this->record)
            )
            ->modalWidth("md")
            ->action(function ($data) {
                $sk = $this->record->pengurus_sk->sk_pengurus;

                $this->record->pengurus_mwcnu()->create([
                    'jemaah_id' => $data['jemaah_id'],
                    'jabatan' => $data['jabatan'] ?? '',
                    'posisi' => $data['posisi'],
                    "surat_keputusan_mwcnu_id" => $sk->id,
                ]);

                $this->record->save();

                $this->mount();
            });
    }

    public function addLembaga(): Action
    {
        return Action::make("addLembaga")
            ->icon("heroicon-m-plus")
            ->disabled(fn() => !$this->getSuratKeputusan())
            ->label("Tambah Pengurus")
            ->successNotificationTitle("Berhasil menambah Pengurus Lembaga")
            ->form(
                UseLembagaModalForm::schema($this->record, LembagaMaster::class, "lembaga_id")
            )
            ->modalWidth("md")
            ->action(function ($data) {
                $sk = $this->record->pengurus_sk->sk_lembaga;

                $this->record->lembaga_mwcnu()->create([
                    "jabatan" => $data['jabatan'],
                    "jemaah_id" => $data['jemaah_id'],
                    "lembaga_id" => $data['lembaga_id'],
                    "surat_keputusan_mwcnu_id" => $sk->id,
                ]);

                $this->record->save();

                $this->mount();
            });
    }

    public function addBanom(): Action
    {
        return Action::make("addBanom")

            ->icon("heroicon-m-plus")
            ->disabled(fn() => !$this->getSuratKeputusan())
            ->label("Tambah Pengurus")
            ->form(
                UseLembagaModalForm::schema($this->record, BanomMaster::class, "banom_id")
            )
            ->modalWidth("md")
            ->successNotificationTitle("Berhasil menambah Pengurus Banom")
            ->action(function ($data) {
                $sk = $this->record->pengurus_sk->sk_banom;

                $this->record->banom_mwcnu()->create([
                    "jabatan" => $data['jabatan'],
                    "jemaah_id" => $data['jemaah_id'],
                    "banom_id" => $data['banom_id'],
                    "surat_keputusan_mwcnu_id" => $sk->id,
                ]);

                $this->record->save();

                $this->mount();
            });
    }

    public function addRantingNu(): Action
    {
        return Action::make("addRantingNU")
            ->icon("heroicon-m-plus")
            ->disabled(fn() => !$this->getSuratKeputusan())
            ->label("Tambah Pengurus")
            ->form(
                UseRantingModalForm::schema($this->record)
            )
            ->successNotificationTitle("Berhasil menambah Ranting NU")
            ->modalWidth("md")
            ->action(function (array $data) {
                $sk = $this->record->pengurus_sk->sk_ranting_nu;

                $this->record->ranting_nu()->create([
                    "posisi" => $data['posisi'],
                    "jabatan" => $data['jabatan'],
                    "jemaah_id" => $data['jemaah_id'],
                    "village_id" => $data['village_id'],
                    "surat_keputusan_mwcnu_id" => $sk->id,
                ]);

                $this->record->save();

                $this->mount();
            });
    }

    public function addAnakRanting(): Action
    {
        return Action::make("addAnakRanting")

            ->icon("heroicon-m-plus")
            ->disabled(fn() => !$this->getSuratKeputusan())
            ->label("Tambah Pengurus")
            ->form(
                UseRantingModalForm::schema($this->record)
            )
            ->successNotificationTitle("Berhasil menambah Anak Ranting")
            ->modalWidth("md")
            ->action(function (array $data) {
                $sk = $this->record->pengurus_sk->sk_anak_ranting;

                $this->record->anak_ranting()->create([
                    "posisi" => $data['posisi'],
                    "jabatan" => $data['jabatan'],
                    "jemaah_id" => $data['jemaah_id'],
                    "village_id" => $data['village_id'],
                    "surat_keputusan_mwcnu_id" => $sk->id,
                ]);

                $this->record->save();

                $this->mount();
            });
    }
}
