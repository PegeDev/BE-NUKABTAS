<?php

namespace App\Filament\Components;

use App\Models\Mwcnu;
use App\Models\Role;
use App\Models\User;
use Filament\Forms\Components\{DateTimePicker, Grid, TextInput};
use Illuminate\Support\Str;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Hash;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class UseManageAdminModal
{

    public static function schema(): array
    {
        return [
            Actions::make([
                FormAction::make("createUser")
                    ->button()
                    ->label("Buat Admin")
                    ->outlined()
                    ->visible(fn(Mwcnu $record) => !$record->admin_id)
                    ->action(function (Mwcnu $record, Set $set) {

                        $passwd = Str::lower(Str::replace(" ", "_", $record->nama_kecamatan)) . "@" . mt_rand(100000, 999999);
                        $user = $record->user()->create([
                            "name" => "MWC " . $record->nama_kecamatan,
                            "email" => "mwc_" . Str::lower(Str::replace(" ", "_", $record->nama_kecamatan)) . "@nukabtas.or.id",
                            "profile_picture" => "https://ui-avatars.com/api/?name=" . Str::slug("MWC " . $record->nama_kecamatan) . "&background=random&bold=true",
                            "email_verified_at" => now(),
                            "password" => Hash::make($passwd),
                        ]);
                        $record->user()->associate($user);

                        $role = Role::where("name", "admin_kecamatan")->first();

                        $role->users()->attach($user->id);
                        $role->save();
                        $record->save();

                        $set("name", $user->name);
                        $set("email", $user->email);
                        $set("paswd", ($passwd));
                        $set("created_at", $user->created_at);
                    })
                    ->successNotificationTitle("Admin Berhasil dibuat")
            ])
                ->fullWidth(),
            Grid::make()
                ->schema([
                    Grid::make()
                        ->schema([
                            TextInput::make("name")
                                ->readOnly()
                                ->live()
                                ->hintAction(
                                    fn() =>
                                    CopyAction::make()
                                        ->copyable(fn($state) => $state)
                                )
                                ->label("Nama Lengkap"),
                            TextInput::make("email")
                                ->extraAttributes([
                                    "class" => "truncate"
                                ])
                                ->live()
                                ->readOnly()
                                ->label("Email")
                                ->hintAction(
                                    fn() =>
                                    CopyAction::make()
                                        ->copyable(fn($state) => $state)
                                )
                        ]),
                    Grid::make()
                        ->schema([
                            TextInput::make("paswd")
                                ->default("1234567890")
                                ->hintAction(
                                    fn() =>
                                    CopyAction::make()
                                        ->visible(fn($state) => $state !== null)
                                        ->copyable(fn($state) => $state)
                                )
                                ->suffixAction(
                                    FormAction::make('toggle-password-visibility')
                                        ->icon('heroicon-o-arrows-right-left')
                                        ->tooltip("Reset Password")
                                        ->label("Ganti Password")
                                        ->iconSize('md')
                                        ->action(function ($component, Set $set, Mwcnu $record) {

                                            $password = Str::lower(Str::replace(" ", "_", $record->nama_kecamatan)) . "@" . mt_rand(100000, 999999);

                                            $user = User::find($record->admin_id);

                                            $user->password = Hash::make($password);
                                            $user->remember_token = null;

                                            $user->save();

                                            $set('paswd', $password);

                                            $component->type("text");
                                        })

                                )
                                ->live()
                                ->readOnly()
                                ->type("text")
                                ->label("Password"),
                            DateTimePicker::make("created_at")
                                ->readOnly()
                                ->live()
                                ->format("Y-m-d H:i:s")
                                ->label("Dibuat"),

                        ])

                ])
                ->visible(fn(Mwcnu $record) => $record->admin_id)
                ->columns(2)

        ];
    }
}
