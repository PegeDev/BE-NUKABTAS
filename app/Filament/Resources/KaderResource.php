<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KaderResource\Pages;
use App\Filament\Resources\KaderResource\RelationManagers;
use App\Models\Jemaah;
use App\Models\Kader;
use App\Tables\Columns\ImageTextColumn;
use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class KaderResource extends Resource
{
    protected static ?string $model = Kader::class;

    protected static ?string $navigationIcon = 'fluentui-person-20-o';

    protected static ?string $navigationLabel = "Data Kader";



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
            ->emptyStateHeading("Data Kader tidak ditemukan")
            ->emptyStateIcon("fluentui-person-delete-20-o")
            ->searchable(true)
            ->striped()
            ->recordUrl(fn(Kader $record) => JamaahResource::getUrl("detail", ["record" => $record->jemaah->id]))
            ->openRecordUrlInNewTab()
            ->columns([
                ImageTextColumn::make("jemaah.nama_lengkap")
                    ->searchable(['jemaahs.nama_lengkap', 'jemaahs.nik'])
                    ->state(fn($record) => $record->jemaah)
                    ->label("NAMA LENGKAP/NIK"),
                TextColumn::make('jemaah.jenis_kelamin')
                    ->description(fn(Kader $record): string => (Carbon::parse($record->jemaah->tanggal_lahir)->age ?? "") . " Tahun")
                    ->weight(FontWeight::SemiBold)
                    ->formatStateUsing(fn($state) => Str::title($state))
                    ->label("GENDER/UMUR")
                    ->size(TextColumnSize::Small),
                TextColumn::make('jemaah.telp')
                    ->formatStateUsing(fn($state) => "+" . str_replace(array('+', ',', '(', ')', ' '), '', $state))
                    ->description(fn(Kader $record): string => Str::limit($record->jemaah->email ?? "-", 26, '...'))
                    ->tooltip(fn(Kader $record): string => $record->jemaah->email ?? "email belum ditambahkan!")
                    ->weight(FontWeight::SemiBold)
                    ->label("TELEPON/EMAIL")
                    ->size(TextColumnSize::Small),
                TextColumn::make('jemaah.alamat_jemaah')
                    ->formatStateUsing(function ($state) {
                        return Str::title($state->provinsi()->first()->name);
                    })
                    ->description(function ($state) {
                        $findCity =  $state ? $state->kota()->first()->name : null;
                        $findDistrict =   $state ? $state->kecamatan()->first()->name : null;
                        return Str::title($findDistrict  . ", " .  str_replace("KABUPATEN", "Kab. ", $findCity));
                    })
                    ->placeholder("-")
                    ->weight(FontWeight::SemiBold)
                    ->label("ALAMAT")
                    ->size(TextColumnSize::Small),

                TextColumn::make("jemaah.pekerjaan")
                    ->formatStateUsing(function ($state) {
                        return Str::title($state);
                    })
                    ->placeholder("-")
                    ->label("PEKERJAAN"),
                TextColumn::make('kaderisasi')
                    ->label("KADERISASI")
                    ->badge()
                    ->color("warning")
                    ->placeholder("-")
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Action::make("detailJemaah")
                        ->url(fn(Kader $record) => JamaahResource::getUrl("detail", ["record" => $record->jemaah_id])),
                ])
            ])
            ->bulkActions([
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
            'index' => Pages\ListKaders::route('/'),
            'create' => Pages\CreateKader::route('/create'),
            'edit' => Pages\EditKader::route('/{record}/edit'),
        ];
    }
}
