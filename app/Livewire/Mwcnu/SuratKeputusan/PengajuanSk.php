<?php

namespace App\Livewire\Mwcnu\SuratKeputusan;

use App\Models\Mwcnu;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class PengajuanSk extends Component  implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public Mwcnu $record;



    public function table(Table $table): Table
    {
        return $table
            ->relationship(fn(): HasMany => $this->record->surat_keputusan_mwcnu())
            ->heading('Pengajuan SK')
            ->emptyStateIcon("ionicon-documents-outline")
            ->emptyStateHeading("Belum ada Permintaan Pengajuan SK")
            ->columns([
                TextColumn::make('name'),
            ])
            ->headerActions([
                CreateAction::make("pengajuan_sk")
                    ->label("Buat Pengajuan SK")
                    ->icon("ionicon-documents-outline")
                    ->form([
                        TextInput::make("surat_permohonan")
                            ->label("Surat Permohonan")
                            ->required(),
                        Grid::make()
                            ->columns([
                                "md" => 2
                            ])
                            ->schema([
                                FileUpload::make('ba_konferensi')
                                    ->label("Berita Acara Konferensi")
                                    ->hint(function () {
                                        return new HtmlString(Blade::render('<x-filament::link icon="ionicon-document-text-outline" target="_blank" href="">Template</x-filament::link>'));
                                    })
                                    ->directory("/pengajuan-sk/" . $this->record->nama_kecamatan)
                                    ->acceptedFileTypes(["application/pdf"])
                                    ->required(),
                                FileUpload::make('ba_rapat_formatur')
                                    ->label("Berita Acara Rapat Formatur")
                                    ->hint(function () {
                                        return new HtmlString(Blade::render('<x-filament::link icon="ionicon-document-text-outline" target="_blank" href="">Template</x-filament::link>'));
                                    })
                                    ->directory("/pengajuan-sk/" . $this->record->nama_kecamatan)
                                    ->acceptedFileTypes(["application/pdf"])
                                    ->required(),
                                FileUpload::make('daftar_riwayat_hidup')
                                    ->hint(function () {
                                        return new HtmlString(Blade::render('<x-filament::link icon="ionicon-document-text-outline" target="_blank" href="">Template</x-filament::link>'));
                                    })
                                    ->directory("/pengajuan-sk/" . $this->record->nama_kecamatan)
                                    ->acceptedFileTypes(["application/pdf"])
                                    ->required(),
                                FileUpload::make('kta')
                                    ->label("Kartu Tanda Anggota (KTA)")
                                    ->acceptedFileTypes(["application/pdf"])
                                    ->hint(function () {
                                        return new HtmlString(Blade::render('<x-filament::link icon="ionicon-document-text-outline" target="_blank" href="">Template</x-filament::link>'));
                                    })
                                    ->directory("/pengajuan-sk/" . $this->record->nama_kecamatan),
                                FileUpload::make('sertifikat_kaderisasi')
                                    ->label("Sertifikat Kaderisasi dan Pengurus Harian Tanfidziyah")
                                    ->acceptedFileTypes(["application/pdf"])
                                    ->hint(function () {
                                        return new HtmlString(Blade::render('<x-filament::link icon="ionicon-document-text-outline" target="_blank" href="">Template</x-filament::link>'));
                                    })
                                    ->directory("/pengajuan-sk/" . $this->record->nama_kecamatan),
                                FileUpload::make('kesediaan')
                                    ->hint(function () {
                                        return new HtmlString(Blade::render('<x-filament::link icon="ionicon-document-text-outline" target="_blank" href="">Template</x-filament::link>'));
                                    })
                                    ->directory("/pengajuan-sk/" . $this->record->nama_kecamatan)
                                    ->acceptedFileTypes(["application/pdf"])
                                    ->required(),
                                FileUpload::make('ktp')
                                    ->label("Kartu Tanda Penduduk (KTP)")
                                    ->hint(function () {
                                        return new HtmlString(Blade::render('<x-filament::link icon="ionicon-document-text-outline" target="_blank" href="">Template</x-filament::link>'));
                                    })
                                    ->directory("/pengajuan-sk/" . $this->record->nama_kecamatan)
                                    ->acceptedFileTypes(["application/pdf"])
                                    ->required(),

                            ]),
                        Grid::make()
                            ->columns([
                                "md" => 2
                            ])
                            ->schema([
                                FileUpload::make('dok_ba_konferensi')
                                    ->label("Dokumentasi Berita Acara Konferensi")
                                    ->image()
                                    ->multiple()
                                    ->maxFiles(4)
                                    ->maxSize(2 * 1024)
                                    ->panelLayout('grid')
                                    ->imageEditor()
                                    ->optimize("webp")
                                    ->required(),
                                FileUpload::make('dok_ba_rapat_formatur')
                                    ->label("Dokumentasi Berita Acara Rapat Formatur")
                                    ->image()
                                    ->multiple()
                                    ->maxFiles(4)
                                    ->maxSize(2 * 1024)
                                    ->panelLayout('grid')
                                    ->imageEditor()
                                    ->optimize("webp")
                                    ->required(),
                            ])

                    ])
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
        return view('livewire.mwcnu.surat-keputusan.pengajuan-sk');
    }
}
