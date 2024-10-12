<?php


namespace App\Filament\Components;

use App\Enums\Position;
use App\Models\LembagaMaster;
use App\Models\Mwcnu;
use Carbon\Carbon;
use Filament\Forms\Components\{FileUpload, Grid, Select, Tabs, TextInput};
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;




class UseLembagaModalForm
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

    public static function getCleanOptionLembaga(Model $model): string
    {
        return view('forms.components.option-lembaga')
            ->with('name', $model?->name)
            ->with('logo', $model?->logo ? Storage::url($model?->logo) : "/logo-nu.png")
            ->render();
    }


    public static function schema($record, $model, string $key_lembaga, $isEditable = false): array
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
                ->getOptionLabelUsing(function ($value) use ($record): string {
                    $user = $record->jemaahs()->find($value);

                    return static::getCleanOptionString($user);
                })
                ->label("Warga"),

            Select::make($key_lembaga)
                ->allowHtml()
                ->searchable()
                ->required()
                ->options(
                    function () use ($model) {
                        $lembaga = $model::all();

                        return $lembaga->mapWithKeys(function ($lembaga) {
                            return [$lembaga->getKey() => static::getCleanOptionLembaga($lembaga)];
                        })->toArray();
                    }

                )
                ->getSearchResultsUsing(function (string $search) use ($model) {
                    $lembaga = $model::where('name', 'like', "%{$search}%")->limit(50)->get();

                    return $lembaga->mapWithKeys(function ($lembaga) {
                        return [$lembaga->getKey() => static::getCleanOptionLembaga($lembaga)];
                    })->toArray();
                })
                ->getOptionLabelUsing(function ($value) use ($model): string {
                    $lembaga = $model::find($value)->first();

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
        ];
    }
}
