<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Position: string implements HasLabel
{
    case Mustasyar = "Mustasyar";
    case Syuriyah = "Syuriyah";
    case Tanfidziyah = "Tanfidziyah";


    public function getLabel(): ?string
    {
        return $this->name;
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Mustasyar => 'primary',
            self::Syuriyah => 'warning',
            self::Tanfidziyah => 'info',
            default => 'default',
        };
    }

    public function getJabatan($posisi): array
    {
        return match ($posisi) {
            default => [],
            self::Syuriyah->getLabel() => [
                'rais' => 'Rais',
                'wakil_rais' => 'Wakil Rais',
                'katib' => 'Katib',
                'wakil_katib' => 'Wakil Katib',
                "awan" => "A'Wan"
            ],
            self::Tanfidziyah->getLabel() => [
                "ketua" => "Ketua",
                "wakil_ketua" => "Wakil Ketua",
                "sekertaris" => "Sekertaris",
                "wakil_sekertaris" => "Wakil Sekertaris",
                "bendahara" => "Bendahara",
                "wakil_bendahara" => "Wakil Bendahara",
            ]
        };
    }
}
