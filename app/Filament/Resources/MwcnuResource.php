<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MwcnuResource\Pages;
use App\Filament\Resources\MwcnuResource\RelationManagers;
use App\Imports\MwcnuImport;
use App\Models\Mwcnu;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Infolist;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class MwcnuResource extends Resource
{
    protected static ?string $model = Mwcnu::class;

    protected static ?string $navigationLabel = 'Data Kecamatan';

    protected static ?string $title = 'Data Kecamatan';

    protected static ?string $clusterBreadcrumb = 'cluster';

    protected static ?string $slug = "data-kecamatan";

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    // protected static ?string $breadcrumbs = 'Data Kecamatan';

    public static function getBreadcrumb(): string
    {
        return 'Data Kecamatan';
    }



    public function getTitle(): string | Htmlable
    {
        return __('Data Kecamatan');
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make("nama_kecamatan")
                    ->label("Kecamatan")
                    ->disabled(true),
                TextColumn::make("jemaah_count")
                    ->counts("jemaahs")
                    ->numeric()
                    ->badge(fn($record) => $record ?? 0)
                    ->label("Jamaah")

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make("import_kecamatan")
                    ->label("Import Kecamatan")
                    ->icon("heroicon-o-document-arrow-down")
                    ->form([
                        FileUpload::make("attachment")
                            ->acceptedFileTypes(["application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/vnd.ms-excel"])
                    ])
                    ->action(
                        function (array $data) {
                            $file = public_path('storage/' . $data["attachment"]);
                            Excel::import(new MwcnuImport, $file);
                        }
                    )
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('detail')
                        ->url(fn(Mwcnu $record) => route('filament.dashboard.resources.data-kecamatan.detail', ['record' => $record]))
                        ->icon("heroicon-o-eye")
                        ->after(function ($record) {
                            return $record;
                        })
                ])
                    ->icon("heroicon-o-ellipsis-vertical")

            ])
            ->recordUrl(
                fn(Mwcnu $record): string => route('filament.dashboard.resources.data-kecamatan.detail', ['record' => $record]),
            )
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
            'index' => Pages\ListMwcnus::route('/'),
            'create' => Pages\CreateMwcnu::route('/create'),
            'edit' => Pages\EditMwcnu::route('/{record}/edit'),
            'detail' => Pages\ViewMwcnu::route('/{record}'),
            'jamaah' => Pages\JamaahMwcnu::route('/{record}?state=jamaah'),
            'create-jamaah' => Pages\CreateJamaahMwcnu::route('/{record}/create-jamaah'),
        ];
    }
}
