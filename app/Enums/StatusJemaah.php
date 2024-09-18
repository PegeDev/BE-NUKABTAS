<?php


namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum StatusJemaah: string implements HasLabel
{
    case Warga = 'Warga';
    case Pengurus = 'Pengurus';

    public function getLabel(): ?string
    {
        return $this->value;
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Pengurus => 'primary',
            self::Warga => 'success',
            default => 'default',
        };
    }
}
