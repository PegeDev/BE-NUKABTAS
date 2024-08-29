<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Permission;
use App\Models\Role;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 1;




    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                TextInput::make("name")
                    ->minLength(2)
                    ->maxLength(255)
                    ->columns(2),
                Select::make("guard_name")
                    ->options([
                        "web" => "web",
                        "api" => "api",
                    ])
                    ->default("web"),
                Section::make("Izin / Permission")
                    ->schema([
                        CheckboxList::make("permissions")
                            ->relationship(
                                modifyQueryUsing: fn(Builder $query) => $query->orderBy(DB::raw('SUBSTRING_INDEX(name, " ", -1)'))
                            )
                            ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->name} ")
                            ->columns(5)
                            ->selectAllAction(
                                fn(Action $action) => $action->label('Select all'),
                            )


                    ])
                    ->hiddenLabel(true)


            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")
                    ->sortable()
                    ->searchable(),
                TextColumn::make("guard_name")
                    ->sortable()
                    ->searchable(),
                TextColumn::make("users_count")
                    ->label("Pengguna")
                    ->counts("users")
                    ->numeric()
                    ->badge(function ($record) {
                        return $record;
                    }),
                TextColumn::make("permissions_count")
                    ->label("Izin")

                    ->counts("permissions")
                    ->numeric()
                    ->badge(function ($record) {
                        return $record;
                    })->color("primary")


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
