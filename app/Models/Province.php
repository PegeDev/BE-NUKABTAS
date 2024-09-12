<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravolt\Indonesia\Models\Province as ModelsProvince;

class Province extends ModelsProvince
{

    use HasFactory;

    public function alamat_jemaah(): HasOne
    {
        return $this->hasOne(AlamatJemaah::class, 'provinsi', 'code');
    }
}
