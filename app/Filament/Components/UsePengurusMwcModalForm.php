<?php


namespace App\Filament\Components;

use App\Enums\Position;
use App\Models\Mwcnu;
use Carbon\Carbon;
use Filament\Forms\Components\{FileUpload, Grid, Select, Tabs, TextInput};
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;




class UsePengurusMwcModalForm
{

    public static function getCleanOptionString(Model $model): string
    {
        return view('forms.components.option-warga')
            ->with('name', $model?->nama_lengkap)
            ->with('address', $model?->alamat_jemaah)
            ->with('image', $model->profile_picture ? Storage::url($model?->profile_picture) : ($model?->jenis_kelamin === "laki-laki" ? "/avatar_male.png" : "/avatar_female.png"))
            ->with('status', Str::upper($model->kepengurusan_type?->type ?? "WARGA"))
            ->render();
    }
    public static function schema(Mwcnu $record, bool $isEditable = false): array
    {
        return [
            Select::make("jemaah_id")
                ->allowHtml()
                ->required()
                ->searchable()
                ->disabled(fn() => $isEditable)
                ->options(function () use ($record) {
                    $users = $record->jemaahs()
                        ->whereNotIn('id', $record->all_pengurus()->pluck('jemaah_id'))
                        ->get();

                    return $users->mapWithKeys(function ($user) {
                        return [$user->getKey() => static::getCleanOptionString($user)];
                    })->toArray();
                })
                ->getSearchResultsUsing(function (string $search) use ($record) {
                    $users = $record->jemaahs()
                        ->whereNotIn('id', $record->all_pengurus()->pluck('jemaah_id'))
                        ->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")->limit(50)->get();

                    return $users->mapWithKeys(function ($user) {
                        return [$user->getKey() => static::getCleanOptionString($user)];
                    })->toArray();
                })
                ->label('Pilih / Cari Warga')
                ->getOptionLabelUsing(function ($value) use ($record): string {
                    $user = $record->jemaahs()->find($value);

                    return static::getCleanOptionString($user);
                })
                ->label("Warga"),
            Grid::make()
                ->columns(2)
                ->schema([
                    Select::make("posisi")
                        ->required()
                        ->options(Position::class)
                        ->native(false)
                        ->preload()
                        ->live()
                        ->placeholder("Pilih Posisi")
                        ->afterStateUpdated(fn(Set $set) => $set("jabatan", null))
                        ->label("Posisi"),
                    Select::make("jabatan")
                        ->live()
                        ->required(fn(Get $get) => $get('posisi') !== Position::Mustasyar->getLabel())
                        ->disabled(fn(Get $get) => $get('posisi') == Position::Mustasyar->getLabel())
                        ->options(function (Get $get) {
                            if ($get('posisi') == Position::Syuriyah->getLabel()) {
                                return Position::Syuriyah->getJabatan($get('posisi'));
                            } else if ($get('posisi') == Position::Tanfidziyah->getLabel()) {
                                return Position::Tanfidziyah->getJabatan($get('posisi'));
                            }
                            return null;
                        })
                        ->placeholder("Pilih Jabatan")
                        ->preload()
                        ->native(false)
                        ->label("Jabatan"),
                ]),
        ];
    }
}
