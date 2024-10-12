<?php

namespace App\Filament\Resources\MwcnuResource\Pages;

use App\Enums\MwcnuStatus as EnumsMwcnuStatus;
use App\Filament\Resources\MwcnuResource;
use App\Models\MwcnuStatus;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditMwcnu extends EditRecord
{
    protected static string $resource = MwcnuResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Detail Kecamatan ' . $this->record->nama_kecamatan;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('detail', ["record" => $this->record->id]);
    }

    protected function afterSave(): void
    {
        if (!$this->record->status()->exists()) {

            $this->record->status()->create([
                "mwcnu_id" => $this->record->id,
                "status" => EnumsMwcnuStatus::DITINJAU
            ]);

            $recipient = User::role(['super_admin', 'admin_kabupaten'])->get();

            Notification::make()
                ->title(fn(): string => "Kecamatan " . $this->record->nama_kecamatan . " Ditinjau")
                ->info()
                ->send()
                ->actions([
                    Action::make('view')
                        ->icon('heroicon-o-eye')
                        ->badge()
                        ->color('gray')
                        ->extraAttributes(["class" => "ring-0"])
                        ->label('lihat')
                        ->markAsRead()
                        ->url($this->getResource()::getUrl('detail', ["record" => $this->record->id]))
                ])
                ->sendToDatabase($recipient);
        }
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Detail Kecamatan berhasil diperbarui';
    }
}
