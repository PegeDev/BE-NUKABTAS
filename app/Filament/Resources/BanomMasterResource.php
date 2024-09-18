<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BanomMasterResource\Pages;
use App\Filament\Resources\BanomMasterResource\RelationManagers;
use App\Imports\BanomImport;
use App\Models\BanomMaster;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Maatwebsite\Excel\Facades\Excel;

class BanomMasterResource extends Resource implements HasShieldPermissions

{
    protected static ?string $model = BanomMaster::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = 'Data Banom';

    protected static ?string $navigationLabel = 'Data Banom';

    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Nama')
                            ->maxLength(255),
                        FileUpload::make('logo')
                            ->image()
                            ->optimize("webp")
                            ->maxSize(2 * 1024),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('logo')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make("import_banom")
                    ->label("Import Daftar Banom")
                    ->icon("heroicon-o-document-arrow-down")
                    ->form([
                        FileUpload::make("attachment")
                            ->acceptedFileTypes(["application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/vnd.ms-excel"])
                    ])
                    ->visible(auth()->user()->can("import_banom::master"))
                    ->action(
                        function (array $data) {
                            $file = public_path('storage/' . $data["attachment"]);
                            Excel::import(new BanomImport, $file);
                        }
                    )
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            "import",
        ];
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBanomMasters::route('/'),
            'create' => Pages\CreateBanomMaster::route('/create'),
            'edit' => Pages\EditBanomMaster::route('/{record}/edit'),
        ];
    }
}
