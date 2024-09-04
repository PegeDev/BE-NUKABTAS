<?php

namespace App\Filament\Resources\MwcnuResource\Pages;

use App\Filament\Resources\MwcnuResource;
use App\Models\Mwcnu;
use App\Models\MwcnuStatus;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

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
        // dd($this->record->status()->exists());
        if (!$this->record->status()->exists()) {

            $this->record->status()->create([
                "status" => "Ditinjau",
                "mwcnu_id" => $this->record->id
            ]);

            // $this->record->status()->associate($status);
            $this->record->status()->save();

            $recipient = User::role(['super_admin', 'admin_kabupaten'])->get();
            Notification::make()
                ->title(fn(): string => "Kecamatan " . $this->record->nama_kecamatan . " Ditinjau")
                ->sendToDatabase($recipient);
        }
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Detail Kecamatan berhasil diubah';
    }
}
