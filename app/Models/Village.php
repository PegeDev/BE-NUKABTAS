<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravolt\Indonesia\Models\Village as ModelsVillage;

class Village extends ModelsVillage
{
    use HasFactory;

    public function alamat_jemaah(): HasMany
    {
        return $this->hasMany(AlamatJemaah::class, "desa");
    }
}
