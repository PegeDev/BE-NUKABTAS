<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravolt\Indonesia\Models\Province as ModelsProvince;

class Province extends ModelsProvince
{

    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'country_id',
    ];

    protected $casts = [
        'name' => 'string',
    ];

    public function alamat_jemaah(): HasMany
    {
        return $this->hasMany(AlamatJemaah::class, "provinsi");
    }
}
