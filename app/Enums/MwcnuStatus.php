<?php

namespace App\Enums;

enum MwcnuStatus: string
{
    case DISETUJUI = "DISETUJUI";
    case DITINJAU = "DITINJAU";


    public function getLabel(): ?string
    {
        return $this->name;
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::DISETUJUI => 'primary',
            self::DITINJAU => 'warning',
            default => 'default',
        };
    }
}
