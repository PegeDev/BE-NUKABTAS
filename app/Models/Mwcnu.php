<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles;

class Mwcnu extends Model
{
    use HasFactory;
    use HasRoles;


    protected $fillable = [
        "nama_kecamatan"
    ];

    public function jemaahs(): HasMany
    {
        return $this->hasMany(Jemaah::class);
    }

    public function pengurus(): HasMany
    {
        return $this->hasMany(PengurusMwcnu::class);
    }

    public function form_mwcnu(): HasOne
    {
        return $this->hasOne(FormMwcnu::class);
    }
}
