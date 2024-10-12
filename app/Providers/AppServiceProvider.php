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

        // FilamentView::registerRenderHook(
        //     PanelsRenderHook::BODY_START,
        //     fn(): string => Blade::render('@livewire(\'components.error-modal\')'),
        // );

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
