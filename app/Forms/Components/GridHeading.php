<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Component;

class GridHeading extends Component
{
    protected string $view = 'forms.components.grid-heading';


    public static function make(): static
    {
        return app(static::class);
    }
}
