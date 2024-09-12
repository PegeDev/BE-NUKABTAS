<?php

namespace App\Filament\Pages\Auth;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;

class EditProfile extends BaseEditProfile
{

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Berhasil update profil';
    }




    public function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Section::make('Informasi Akun')
                    ->columnSpan(6)
                    ->schema([
                        Grid::make()
                            ->columns(1)
                            ->extraAttributes([
                                "class" => 'flex flex-col justify-center items-center'
                            ])
                            ->schema([
                                FileUpload::make("profile_picture")
                                    ->columnSpanFull()
                                    ->avatar()
                                    ->inlineLabel(false)
                                    ->imageEditor()
                                    ->previewable()
                                    ->hiddenLabel()
                                    ->image()
                            ]),
                        Grid::make()
                            ->columnSpanFull()
                            ->columns(1)
                            ->schema([
                                TextInput::make('name')
                                    ->inlineLabel(false)
                                    ->label("Nama Lengkap"),
                                TextInput::make('email')
                                    ->inlineLabel(false)
                                    ->readOnly(true)
                                    ->label("Email"),
                                TextInput::make('password')
                                    ->password()
                                    ->revealable()
                                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                                    ->dehydrated(fn($state) => filled($state))
                                    ->required(fn(Page $livewire) => ($livewire instanceof CreateUser))
                                    ->maxLength(255),

                            ])
                    ]),

            ]);
    }
}
