<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MwcnuResource\Pages;
use App\Imports\MwcnuImport;
use App\Models\DetailMwcnu;
use App\Models\Mwcnu;
use App\Models\Role;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Carbon\Carbon;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class MwcnuResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Mwcnu::class;

    protected static ?string $navigationLabel = 'Data Kecamatan';

    protected static ?string $title = 'Data Kecamatan';


    protected static ?string $slug = "data-kecamatan";

    protected static ?string $navigationIcon = 'ionicon-git-branch';


    public static function getBreadcrumb(): string
    {
        return 'Data Kecamatan';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->columnSpanFull()
                    ->columns(12)
                    ->relationship("detail_mwcnus")
                    ->schema([
                        Section::make("Detail Kecamatan")
                            ->columnSpan([
                                "sm" => 12,
                                "md" => 7
                            ])
                            ->schema([
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make("nama_ketua")
                                            ->placeholder("Nama Lengkap")
                                            ->required()
                                            ->label("Nama Ketua"),
                                        TextInput::make("telp_ketua")
                                            ->placeholder("Nomor Telepon / Whatsapp")
                                            ->prefix("+62")
                                            ->mask("999 9999 99999")
                                            ->required()
                                            ->tel()
                                            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                                            ->label("Nomor Telp. Ketua"),

                                    ]),
                                Grid::make()
                                    ->columns(2)
                                    ->schema([

                                        TextInput::make("email")
                                            ->placeholder("contoh: test@gmail.com")
                                            ->required()
                                            ->label("Email MWCNU"),
                                        TextInput::make("google_maps")
                                            ->url()
                                            ->required()
                                            ->suffixIcon('heroicon-o-globe-alt')
                                            ->placeholder("https://goo.gl/maps/...")
                                            ->label("Lokasi Google Maps"),
                                    ]),

                                Grid::make()
                                    ->columns(1)
                                    ->schema([
                                        Textarea::make("alamat")
                                            ->placeholder("Nama Jalan, Gang, No. Rumah, RT dan RW")
                                            ->required()
                                            ->autosize(false)
                                            ->rows(5)
                                            ->label("Alamat Lengkap (Sekertariatan / Kantor MWCNU)"),
                                    ])
                            ]),
                        Section::make("Informasi Admin")
                            ->columnSpan([
                                "sm" => 12,
                                'md' => 5,
                            ])
                            ->schema([
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make("nama_admin")
                                            ->label("Nama Lengkap")
                                            ->placeholder("Nama Lengkap Admin")
                                            ->required(),
                                        TextInput::make("telp_admin")
                                            ->placeholder("Nomor Telepon / Whatsapp Admin")
                                            ->prefix("+62")
                                            ->mask("999 9999 99999")
                                            ->required()
                                            ->tel()
                                            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                                            ->label("Nomor Telp. Admin")
                                    ]),
                                Grid::make()
                                    ->columns(1)
                                    ->schema([
                                        FileUpload::make("surat_tugas")
                                            ->label("Surat Tugas")
                                            ->maxSize(2048)
                                            ->directory("surat_tugas")
                                            ->getUploadedFileNameForStorageUsing(
                                                fn(DetailMwcnu $record, Get $get): string => (string) str("Surat-Tugas-Admin-" . Str::slug($get("nama_admin")) . ".pdf"),
                                            )
                                            ->acceptedFileTypes(["application/pdf"])
                                            ->required(),
                                    ]),
                                Grid::make()
                                    ->columns(1)
                                    ->schema([
                                        Textarea::make("alamat_admin")
                                            ->required()
                                            ->placeholder("Nama Jalan, Gang, No. Rumah, RT dan RW")
                                            ->autosize(false)
                                            ->rows(3)
                                    ])
                            ])
                    ])
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("nama_kecamatan")
                    ->label("Kecamatan")
                    ->disabled(true),
                // TextColumn::make("jamaah_count")
                //     ->counts("jemaahs")
                //     ->numeric()
                //     ->badge()
                //     ->label("Jamaah"),
                TextColumn::make("user.name")
                    ->label("Admin")
                    ->badge()
                    ->placeholder('admin belum dibuat')
                    ->visible(auth()->user()->can("manage_admin_mwcnu")),
                TextColumn::make("status.status")
                    ->badge()
                    ->color(fn(string $state): string =>  match (Str::lower($state)) {
                        'ditinjau' => 'warning',
                        'disetujui' => 'success',
                    })
                    ->formatStateUsing(fn($state) => Str::upper($state))
                    ->label("Status"),
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
                    ->visible(auth()->user()->can("import_mwcnu"))
                    ->action(
                        function (array $data) {
                            $file = public_path('storage/' . $data["attachment"]);
                            Excel::import(new MwcnuImport, $file);
                        }
                    )
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('lihat_detail')
                        ->url(fn(Mwcnu $record) => route('filament.dashboard.resources.data-kecamatan.detail', ['record' => $record]))
                        ->icon("heroicon-o-eye")
                        ->after(function ($record) {
                            return $record;
                        })
                        ->modalWidth("lg"),
                    Action::make('kepengurusan')
                        ->icon("heroicon-o-user")
                        ->label("Manage Admin")
                        ->fillForm(fn(Mwcnu $record) => $record->user ? $record->user->toArray()  : [])
                        ->form([
                            Grid::make()
                                ->schema([
                                    Actions::make([
                                        FormAction::make("createUser")
                                            ->button()
                                            ->label("Create User")
                                            ->outlined()
                                            ->action(function (Mwcnu $record) {
                                                $user = $record->user()->create([
                                                    "name" => "Kecamatan " . $record->nama_kecamatan,
                                                    "email" => "kec_" . Str::lower($record->nama_kecamatan) . "@nukabtas.or.id",
                                                    "profile_picture" => "",
                                                    "email_verified_at" => now(),
                                                    "password" => Hash::make("password"),
                                                ]);
                                                $record->user()->associate($user);
                                                $record->save();

                                                $role = Role::where("name", "admin_kecamatan")->first();

                                                $role->users()->attach($user->id);
                                                $role->save();
                                            })
                                    ])

                                ])
                                ->visible(fn(Mwcnu $record) => $record->admin_id === null),
                            Grid::make()
                                ->schema([
                                    Grid::make()
                                        ->schema([
                                            TextInput::make("name")
                                                ->disabled()
                                                ->label("Nama Lengkap"),
                                            TextInput::make("email")
                                                ->extraAttributes([
                                                    "class" => "truncate"
                                                ])
                                                ->disabled()
                                                ->label("Email")
                                        ]),
                                    Grid::make()
                                        ->schema([
                                            TextInput::make("paswd")
                                                ->default("1234567890")
                                                ->suffixAction(
                                                    FormAction::make('toggle-password-visibility')
                                                        ->icon('heroicon-o-arrows-right-left')
                                                        ->tooltip("Reset Password")
                                                        ->label("Ganti Password")
                                                        ->iconSize('md')
                                                        ->action(function ($component, Set $set, Mwcnu $record) {

                                                            $password = Str::lower($record->nama_kecamatan) . "@240831";


                                                            $user = User::find($record->admin_id);

                                                            $user->password = Hash::make($password);
                                                            $user->remember_token = null;

                                                            $user->save();

                                                            $set('paswd', $password);

                                                            $component->type("text");
                                                        })

                                                )
                                                ->readOnly()
                                                ->type("password")
                                                ->label("Password"),
                                            DateTimePicker::make("created_at")
                                                ->disabled()
                                                ->format("Y-m-d H:i:s")
                                                ->label("Dibuat"),

                                        ])

                                ])
                                ->columns(2)
                                ->visible(fn(Mwcnu $record) => $record->admin_id !== null),
                        ])
                        ->modalSubmitAction(false)
                        ->modalWidth("lg")
                        ->visible(auth()->user()->can("manage_admin_mwcnu")),

                ])
                    ->label("Action")
                    ->icon("heroicon-o-ellipsis-vertical")

            ])
            ->recordUrl(
                fn(Mwcnu $record): string => route('filament.dashboard.resources.data-kecamatan.detail', ['record' => $record]),
            )
            ->groupedBulkActions([
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


    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            "import",
            "manage_admin",
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMwcnus::route('/'),
            'create' => Pages\CreateMwcnu::route('/create'),
            'edit' => Pages\EditMwcnu::route('/{record}/edit'),
            'detail' => Pages\ViewMwcnu::route('/{record}'),
            'jamaah' => Pages\JamaahMwcnu::route('/{record}?state=warga'),
            'create-jamaah' => Pages\CreateJamaahMwcnu::route('/{record}/create-jamaah'),
        ];
    }
}
