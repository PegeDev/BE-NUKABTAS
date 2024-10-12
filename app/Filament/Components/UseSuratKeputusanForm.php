<?php


namespace App\Filament\Components;

use App\Models\Mwcnu;
use Carbon\Carbon;
use Filament\Forms\Components\{DatePicker, FileUpload, Grid, Select, TextInput};
use Filament\Forms\Get;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UseSuratKeputusanForm
{
    public static function schema(Mwcnu $record, bool $isReadOnly = false): array
    {
        return [
            Grid::make()
                ->columns([
                    "md" => 2
                ])
                ->schema([
                    Select::make("jenis_kepengurusan")
                        ->options(function () use ($record) {
                            $opt = [
                                "pengurus_mwc" => "Pengurus MWC",
                                "ranting_nu" => "Ranting NU",
                                "anak_ranting" => "Anak Ranting",
                                "banom" => "Banom",
                                "lembaga" => "Lembaga",
                            ];
                            $filtered = array_filter($opt, function ($value, $key) use ($record) {
                                return !in_array($key, $record->surat_keputusan_mwcnu->where("end_khidmat", ">", Carbon::now())->pluck("jenis_kepengurusan")->toArray());
                            }, ARRAY_FILTER_USE_BOTH);
                            return $filtered;
                        })
                        ->native(false)
                        ->label("Jenis Kepengurusan")
                        ->disabled(fn() => $isReadOnly)
                        ->placeholder("Pilih jenis kepengurusan")
                        ->required(),
                    TextInput::make("nomor_surat")
                        ->live()
                        ->placeholder("Nomor Surat")
                        ->afterStateUpdated(fn($state) => Str::slug($state))
                        ->formatStateUsing(fn($state) => Str::slug($state))
                        ->label("Nomor Surat")
                        ->required(),
                ]),
            Grid::make()
                ->columns([
                    "md" => 2
                ])
                ->schema([
                    DatePicker::make("start_khidmat")
                        ->required()
                        ->label("Tanggal Mulai"),
                    DatePicker::make("end_khidmat")
                        ->required()
                        ->label("Tanggal Berhenti"),
                ]),
            FileUpload::make('file_surat')
                ->label("Surat Keputusan")
                ->hint(function () {
                    return new HtmlString(Blade::render('<x-filament::link icon="ionicon-document-text-outline" target="_blank" href="">Template</x-filament::link>'));
                })
                ->disabled(fn() => $isReadOnly)
                ->openable()
                ->directory(fn(Get $get) => "/surat-keputusan/" . Str::slug($get("nomor_surat")) . "/"  . Str::slug($record->nama_kecamatan))
                ->acceptedFileTypes(["application/pdf"])
                ->getUploadedFileNameForStorageUsing(
                    fn(TemporaryUploadedFile $file, Get $get): string => (string) Str::slug("SURAT_KEPUTUSAN-" . Str::slug($get("nomor_surat")) . "-" .  $record->nama_kecamatan) . "." . $file->extension(),
                )
                ->required(),
        ];
    }
}
