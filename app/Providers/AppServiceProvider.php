<?php

namespace App\Providers;

use Carbon\Carbon;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
use Inertia\Inertia;
use Tighten\Ziggy\Ziggy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // FilamentView::registerRenderHook(
        //     PanelsRenderHook::TOPBAR_START,
        //     fn(): View => view('livewire.custom-sidebar-collapse')
        // );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Request::macro('hasValidSignature', function ($absolute = true) {
            $uploading = strpos(URL::current(), '/livewire/upload-file');
            $previewing = strpos(URL::current(), '/livewire/preview-file');
            if ($uploading || $previewing) {
                return true;
            }
        });


        Inertia::share([
            'auth' => function (Request $request) {

                if (!$request->user()) {
                    return null;
                }
                $name = str($request->user()->name)
                    ->trim()
                    ->explode(' ')
                    ->map(fn(string $segment): string => filled($segment) ? mb_substr($segment, 0, 1) : '')
                    ->join(' ');
                return [
                    ...$request->user()->only('id', 'name', 'email', 'profile_picture'),
                    "email" => maskEmail($request->user()->email),
                    "profile_picture" =>  'https://ui-avatars.com/api/?name=' . urlencode($name) . "&background=fff&color=076857&bold=true",
                ];
            }
        ]);

        Carbon::setLocale("id");

        FilamentIcon::register([
            'panels::sidebar.collapse-button' => 'heroicon-o-bars-3',
            'panels::sidebar.expand-button' => 'heroicon-o-bars-3',
        ]);

        FilamentColor::register([
            'primary' => Color::hex("#076857"),
        ]);
    }
}
