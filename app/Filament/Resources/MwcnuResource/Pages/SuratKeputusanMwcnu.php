<?php

namespace App\Filament\Resources\MwcnuResource\Pages;

use App\Filament\Resources\MwcnuResource;
use App\Models\Mwcnu;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratKeputusanMwcnu extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = MwcnuResource::class;

    protected static string $view = 'filament.resources.mwcnu-resource.pages.surat-keputusan-mwcnu';

    public Mwcnu $record;

    public function table(Table $table): Table
    {
        return $table
            ->relationship(fn(): HasMany => $this->record->pengajuan_sk_mwcnu())
            ->columns([
                // TextColumn::make("nomor_surat")
                //     ->label("Nomor Surat")
            ]);
    }
}
