<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JamaahResource\Pages;
use Illuminate\Support\Str;
use App\Filament\Resources\JamaahResource\RelationManagers;
use App\Models\Jemaah;
use App\Tables\Columns\ImageTextColumn;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;

class JamaahResource extends Resource
{
    protected static ?string $model = Jemaah::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = "Data Warga";


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageTextColumn::make("nama_lengkap")
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
                TextColumn::make('alamat_lengkap')
                    ->formatStateUsing(function ($state) {
                        // dd(json_decode($state, true));
                        $decode = json_decode($state, true);
                        $provinsi = Province::where("code", $decode["provinsi"])->first()->name ?? "";
                        return Str::title($provinsi);
                    })
                    ->description(function ($state) {
                        $decode = json_decode($state, true);
                        $findCity = City::where("code", $decode["kota"])->first()->name ?? "-";
                        $findDesa = Village::where("code", $decode["desa"])->first()->name ?? "-";
                        return Str::title($findDesa . ", " . str_replace("KABUPATEN", "Kab.", $findCity));
                    })
                    ->weight(FontWeight::SemiBold)
                    ->label("ALAMAT")
                    ->size(TextColumnSize::Small),
                TextColumn::make('kepengurusan')
                    ->formatStateUsing(function ($state) {

                        return Str::title($state);
                    })
                    ->description(function (Jemaah $record) {

                        return Str::title($record->jabatan_kepengurusan);
                    })
                    ->weight(FontWeight::SemiBold)
                    ->label("KEPENGURUSAN")
                    ->size(TextColumnSize::Small),
                TextColumn::make("detail.pekerjaan")
                    ->formatStateUsing(function ($state) {

                        return $state ? Str::title($state) : "-";
                    })
                    ->label("PEKERJAAN")
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
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





    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJamaahs::route('/'),
            'create' => Pages\CreateJamaah::route('/create'),
            'edit' => Pages\EditJamaah::route('/{record}/edit'),
            "detail" => Pages\DetailJamaah::route('/{record}'),
        ];
    }
}
