<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LembagaMasterResource\Pages;
use App\Filament\Resources\LembagaMasterResource\RelationManagers;
use App\Imports\LembagaImport;
use App\Models\LembagaMaster;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Maatwebsite\Excel\Facades\Excel;

class LembagaMasterResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = LembagaMaster::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = 'Data Lembaga';

    protected static ?string $navigationLabel = 'Data Lembaga';

    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('logo')
                    ->maxLength(255),
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
                Action::make("import_lembaga")
                    ->label("Import Daftar Lembaga")
                    ->icon("heroicon-o-document-arrow-down")
                    ->form([
                        FileUpload::make("attachment")
                            ->acceptedFileTypes(["application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/vnd.ms-excel"])
                    ])
                    ->visible(auth()->user()->can("import_lembaga::master"))
                    ->action(
                        function (array $data) {
                            $file = public_path('storage/' . $data["attachment"]);
                            Excel::import(new LembagaImport, $file);
                        }
                    )
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                ])
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


    public static function getRelations(): array
    {
        return [
            //
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLembagaMasters::route('/'),
            'create' => Pages\CreateLembagaMaster::route('/create'),
            'edit' => Pages\EditLembagaMaster::route('/{record}/edit'),
        ];
    }
}
