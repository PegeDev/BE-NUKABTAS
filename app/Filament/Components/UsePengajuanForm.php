<?php


namespace App\Filament\Components;

use App\Models\Mwcnu;
use Carbon\Carbon;
use Filament\Forms\Components\{FileUpload, Grid, Tabs};
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UsePengajuanForm
{
    public static function schema(Mwcnu $record, bool $isReadOnly = false): array
    {
        return [
            Grid::make()
                ->columns([
                    "md" => 2
                ])
                ->schema([
                    FileUpload::make('surat_permohonan')
                        ->label("Surat Permohonan")
                        ->hint(function () {
                            return new HtmlString(Blade::render('<x-filament::link icon="ionicon-document-text-outline" target="_blank" href="">Template</x-filament::link>'));
                        })
                        ->disabled(fn() => $isReadOnly)
                        ->openable()
                        ->directory("/pengajuan-sk/" . Carbon::now()->timestamp . "/" . Str::slug($record->nama_kecamatan))
                        ->acceptedFileTypes(["application/pdf"])
                        ->getUploadedFileNameForStorageUsing(
                            fn(TemporaryUploadedFile $file): string => (string) Str::slug("SURAT_PERMOHONAN_" . $record->nama_kecamatan) . "." . $file->extension(),
                        )
                        ->required(),
                    FileUpload::make('ba_konferensi')
                        ->label("Berita Acara Konferensi")
                        ->openable()
                        ->hint(function () {
                            return new HtmlString(Blade::render('<x-filament::link icon="ionicon-document-text-outline" target="_blank" href="">Template</x-filament::link>'));
                        })
                        ->disabled(fn() => $isReadOnly)
                        ->directory("/pengajuan-sk/" . Carbon::now()->timestamp . "/" . Str::slug($record->nama_kecamatan))
                        ->acceptedFileTypes(["application/pdf"])
                        ->getUploadedFileNameForStorageUsing(
                            fn(TemporaryUploadedFile $file): string => (string) Str::slug("BA_KONFERENSI_" . $record->nama_kecamatan) . "." . $file->extension(),
                        )
                        ->required(),
                    FileUpload::make('ba_rapat_formatur')
                        ->label("Berita Acara Rapat Formatur")
                        ->openable()
                        ->hint(function () {
                            return new HtmlString(Blade::render('<x-filament::link icon="ionicon-document-text-outline" target="_blank" href="">Template</x-filament::link>'));
                        })
                        ->disabled(fn() => $isReadOnly)
                        ->directory("/pengajuan-sk/" . Carbon::now()->timestamp . "/" . Str::slug($record->nama_kecamatan))
                        ->acceptedFileTypes(["application/pdf"])
                        ->getUploadedFileNameForStorageUsing(
                            fn(TemporaryUploadedFile $file): string => (string) Str::slug("BA_RAPAT_FORMATUR_" . $record->nama_kecamatan) . "." . $file->extension(),
                        )
                        ->required(),

                    FileUpload::make('sertifikat_kaderisasi')
                        ->label("Sertifikat Kaderisasi Calon Pengurus")
                        ->openable()
                        ->acceptedFileTypes(["application/pdf"])
                        ->hint(function () {
                            return new HtmlString(Blade::render('<x-filament::link icon="ionicon-document-text-outline" target="_blank" href="">Template</x-filament::link>'));
                        })
                        ->disabled(fn() => $isReadOnly)
                        ->getUploadedFileNameForStorageUsing(
                            fn(TemporaryUploadedFile $file): string => (string) Str::slug("SERTIFIKAT_KADERISASI_" . $record->nama_kecamatan) . "." . $file->extension(),
                        )
                        ->directory("/pengajuan-sk/" . Carbon::now()->timestamp . "/" . Str::slug($record->nama_kecamatan)),
                    FileUpload::make('kesediaan')
                        ->hint(function () {
                            return new HtmlString(Blade::render('<x-filament::link icon="ionicon-document-text-outline" target="_blank" href="">Template</x-filament::link>'));
                        })
                        ->disabled(fn() => $isReadOnly)
                        ->openable()
                        ->directory("/pengajuan-sk/" . Carbon::now()->timestamp . "/" . Str::slug($record->nama_kecamatan))
                        ->acceptedFileTypes(["application/pdf"])
                        ->getUploadedFileNameForStorageUsing(
                            fn(TemporaryUploadedFile $file): string => (string) Str::slug("KESEDIAAN_" . $record->nama_kecamatan) . "." . $file->extension(),
                        )
                        ->required(),
                    FileUpload::make('ktp')
                        ->label("Kartu Tanda Penduduk (KTP)")
                        ->openable()
                        ->hint(function () {
                            return new HtmlString(Blade::render('<x-filament::link icon="ionicon-document-text-outline" target="_blank" href="">Template</x-filament::link>'));
                        })
                        ->disabled(fn() => $isReadOnly)
                        ->directory("/pengajuan-sk/" . Carbon::now()->timestamp . "/" . Str::slug($record->nama_kecamatan))
                        ->acceptedFileTypes(["application/pdf"])
                        ->getUploadedFileNameForStorageUsing(
                            fn(TemporaryUploadedFile $file): string => (string) Str::slug("KTP_" . $record->nama_kecamatan) . "." . $file->extension(),
                        )
                        ->required(),
                    FileUpload::make('daftar_riwayat_hidup')
                        ->hint(function () {
                            return new HtmlString(Blade::render('<x-filament::link icon="ionicon-document-text-outline" target="_blank" href="">Template</x-filament::link>'));
                        })
                        ->disabled(fn() => $isReadOnly)
                        ->openable()
                        ->directory("/pengajuan-sk/" . Carbon::now()->timestamp . "/" . Str::slug($record->nama_kecamatan))
                        ->acceptedFileTypes(["application/pdf"])
                        ->getUploadedFileNameForStorageUsing(
                            fn(TemporaryUploadedFile $file): string => (string) Str::slug("DAFTAR_RIWAYAT_HIDUP_" . $record->nama_kecamatan) . "." . $file->extension(),
                        )
                        ->required(),
                    FileUpload::make('kta')
                        ->label("Kartu Tanda Anggota (KTA)")
                        ->openable()
                        ->acceptedFileTypes(["application/pdf"])
                        ->hint(function () {
                            return new HtmlString(Blade::render('<x-filament::link icon="ionicon-document-text-outline" target="_blank" href="">Template</x-filament::link>'));
                        })
                        ->disabled(fn() => $isReadOnly)
                        ->getUploadedFileNameForStorageUsing(
                            fn(TemporaryUploadedFile $file): string => (string) Str::slug("KTA_" . $record->nama_kecamatan) . "." . $file->extension(),
                        )
                        ->directory("/pengajuan-sk/" . Carbon::now()->timestamp . "/" . Str::slug($record->nama_kecamatan)),

                ]),
            Grid::make()
                ->columns([
                    "md" => $isReadOnly ? 2 : 1
                ])
                ->schema([
                    FileUpload::make('dok_ba_konferensi')
                        ->label("Dokumentasi Berita Acara Konferensi")
                        ->image()
                        ->multiple()
                        ->minFiles(2)
                        ->maxFiles(4)
                        ->maxSize(2 * 1024)
                        ->panelLayout('grid')
                        ->directory("/pengajuan-sk/" . Carbon::now()->timestamp . "/" . Str::slug($record->nama_kecamatan))
                        ->imageEditor()
                        ->optimize("webp")
                        ->disabled(fn() => $isReadOnly)
                        ->openable()
                        ->required(),
                    FileUpload::make('dok_ba_rapat_formatur')
                        ->label("Dokumentasi Berita Acara Rapat Formatur")
                        ->image()
                        ->multiple()
                        ->minFiles(2)
                        ->maxFiles(4)
                        ->maxSize(2 * 1024)
                        ->panelLayout('grid')
                        ->directory("/pengajuan-sk/" . Carbon::now()->timestamp . "/" . Str::slug($record->nama_kecamatan))
                        ->imageEditor()
                        ->optimize("webp")
                        ->disabled(fn() => $isReadOnly)
                        ->openable()
                        ->required(),
                ]),
        ];
    }
}
