<?php

namespace App\Filament\Resources\JamaahResource\Pages;

use App\Filament\Resources\JamaahResource;
use App\Models\Jemaah;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class DetailWarga extends ViewRecord
{
    protected static string $resource = JamaahResource::class;

    protected static ?string $title = "Detail Warga";

    protected static string $view = 'filament.resources.jemaah.pages.detail-warga';

    public function getHeaderActions(): array
    {
        return [
            EditAction::make("edit")
                ->label("Edit Detail")
                ->visible(auth()->user()->id === $this->record->mwcnu->admin_id && auth()->user()->can("update_jamaah") || auth()->user()->hasRole('super_admin'))
                ->icon("heroicon-o-pencil-square")
        ];
    }
}
