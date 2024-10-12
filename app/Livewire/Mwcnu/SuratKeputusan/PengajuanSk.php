<?php

namespace App\Livewire\Mwcnu\SuratKeputusan;

use App\Filament\Components\UsePengajuanForm;
use App\Filament\Resources\MwcnuResource;
use App\Models\Mwcnu;
use App\Models\PengajuanSkMwcnu;
use App\Models\User;
use Filament\Actions\Action as ActionsAction;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action as NotificationsActionsAction;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PengajuanSk extends Component  implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public Mwcnu $record;



    public function table(Table $table): Table
    {
        return $table
            ->relationship(fn(): HasMany => $this->record->pengajuan_sk_mwcnu())
            ->heading('Pengajuan SK')
            ->emptyStateIcon("ionicon-documents-outline")
            ->emptyStateHeading("Belum ada Permintaan Pengajuan SK")
            ->columns([
                TextColumn::make('surat_permohonan')
                    ->url(fn($state) => Storage::url($state))
                    ->openUrlInNewTab()
                    ->limit(24)
                    ->label("Surat Permohonan"),
                TextColumn::make('status')
                    ->label("Status")
                    ->icon(fn(string $state) => match (Str::lower($state)) {
                        "pending" => "fluentui-document-bullet-list-clock-20-o",
                        "canceled" => "fluentui-document-dismiss-20-o",
                        "approved" => "fluentui-document-checkmark-20-o",
                    })
                    ->formatStateUsing(fn($state) => Str::upper($state))
                    ->badge()
                    ->color(fn(string $state) =>  match (Str::lower($state)) {
                        "pending" => Color::Blue,
                        "canceled" => Color::Red,
                        "approved" => "primary",
                    }),
                TextColumn::make('created_at')
                    ->label("Tgl. Pengajuan")
                    ->dateTime("D, d M Y"),
            ])
            ->headerActions([
                CreateAction::make("pengajuan_sk")
                    ->label("Buat Pengajuan SK")
                    ->stickyModalHeader()
                    ->stickyModalFooter()
                    ->icon("ionicon-documents-outline")
                    ->form(
                        UsePengajuanForm::schema($this->record)
                    )
                    ->after(function ($record) {
                        $receipent = User::role(['super_admin', 'admin_kabupaten'])->get();

                        Notification::make()
                            ->title("Surat Pengajuan SK Kecamatan " . $this->record->nama_kecamatan)
                            ->info()
                            ->body("Pengajuan SK Kecamatan " . $this->record->nama_kecamatan . ", diajukan oleh " . auth()->user()->name)
                            ->actions([
                                NotificationsActionsAction::make('view')
                                    ->icon('fluentui-document-bullet-list-clock-20-o')
                                    ->badge()
                                    ->color('gray')
                                    ->label('review')
                                    ->extraAttributes(["class" => "ring-0"])
                                    ->markAsRead()
                                    ->url(MwcnuResource::getUrl('surat-keputusan', ["record" => $this->record->id]))
                            ])
                            ->send()
                            ->sendToDatabase($receipent);
                    })
                    ->visible(fn() => auth()->user()->id === $this->record->admin_id || auth()->user()->hasRole(['admin_kabupaten', 'super_admin']))
                    ->disabled(fn() => $this->record->pengajuan_sk_mwcnu()->where("status", "pending")->exists())
                    ->size(ActionSize::Small),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                ActionGroup::make([
                    Action::make("review")
                        ->label("Review & Upload SK")
                        ->icon("heroicon-o-pencil-square")
                        ->fillForm(fn(PengajuanSkMwcnu $record): array => $record->toArray())
                        ->steps([
                            Step::make('review_surat')
                                ->icon("fluentui-document-search-20-o")
                                ->label("Review Surat Pengajuan")
                                ->schema(
                                    UsePengajuanForm::schema($this->record, true)
                                ),
                            Step::make('upload_surata')
                                ->icon("fluentui-document-arrow-up-20-o")
                                ->label("Upload Surat Keputusan")
                                ->schema([
                                    FileUpload::make('surat_keputusan')
                                        ->label("Surat Keputusan")
                                        ->openable()
                                        ->acceptedFileTypes(["application/pdf"])
                                        ->getUploadedFileNameForStorageUsing(
                                            fn(TemporaryUploadedFile $file): string => (string) Str::slug("SURAT_KEPUTUSAN" . $this->record->nama_kecamatan) . "." . $file->extension(),
                                        )
                                        ->directory("/pengajuan-sk/" . Str::slug($this->record->nama_kecamatan)),
                                ]),
                        ])
                        ->action(function (PengajuanSkMwcnu $record, array $data) {
                            $record->surat_keputusan = $data["surat_keputusan"];
                            $record->status = "approved";
                            $record->save();
                        })
                        ->after(function () {
                            $mwc_admin = User::find($this->record->admin_id);

                            $super_admin = User::role(['super_admin', 'admin_kabupaten'])->get();

                            $receipent = collect([$mwc_admin])->merge($super_admin)->filter();

                            Notification::make()
                                ->title("Surat Pengajuan SK Kecamatan " . $this->record->nama_kecamatan . " disetujui")
                                ->success()
                                ->body("Surat Pengajuan Kecamatan " . $this->record->nama_kecamatan . " berhasil disetujui oleh " . auth()->user()->name)
                                ->actions([
                                    NotificationsActionsAction::make('view')
                                        ->icon('fluentui-document-bullet-list-clock-20-o')
                                        ->badge()
                                        ->color('gray')
                                        ->label('lihat sk')
                                        ->extraAttributes(["class" => "ring-0"])
                                        ->markAsRead()
                                        ->url(MwcnuResource::getUrl('surat-keputusan', ["record" => $this->record->id]))
                                ])
                                ->send()
                                ->sendToDatabase($receipent);
                        })
                        ->visible(fn() => auth()->user()->hasRole(["super_admin", "admin_kabupaten"]))
                        ->size(ActionSize::Small)
                        ->modalCloseButton(false),
                    ViewAction::make("check_keputusan")
                        ->icon("fluentui-document-search-20-o")
                        ->form([
                            FileUpload::make('surat_keputusan')
                                ->label("Surat Keputusan")
                                ->openable()
                                ->acceptedFileTypes(["application/pdf"])
                                ->getUploadedFileNameForStorageUsing(
                                    fn(TemporaryUploadedFile $file): string => (string) Str::slug("SURAT_KEPUTUSAN_" . $this->record->nama_kecamatan) . "." . $file->extension(),
                                )
                                ->directory("/pengajuan-sk/" . Str::slug($this->record->nama_kecamatan)),
                        ])
                        ->size(ActionSize::Small)
                        ->modalHeading("Lihat SK " . $this->record->nama_kecamatan)
                        ->visible(fn(PengajuanSkMwcnu $record) => $record->surat_keputusan)
                        ->label("Lihat SK"),
                    EditAction::make()
                        ->icon("fluentui-document-edit-20-o")
                        ->size(ActionSize::Small)
                        ->visible(fn() => auth()->user()->id === $this->record->admin_id)
                        ->form(UsePengajuanForm::schema($this->record))
                        ->label("Edit Pengajuan"),
                    DeleteAction::make()
                        ->size(ActionSize::Small)
                        ->visible(auth()->user()->hasRole(['admin_kabupaten', 'super_admin']))
                        ->label("Hapus"),

                ])
                    ->visible(fn() => auth()->user()->hasRole(["super_admin", "admin_kabupaten"]) || auth()->user()->id === $this->record->admin_id)
            ])
            ->bulkActions([
                // ...
            ]);
    }


    public function render()
    {
        return view('livewire.mwcnu.surat-keputusan.pengajuan-sk');
    }
}
