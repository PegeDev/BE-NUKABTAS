<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormMwcnu extends Model
{
    use HasFactory;


    protected $fillable = [
        'mwcnu_id',
        "is_enabled",
        "code"
    ];

    protected $casts = [
        "is_enabled" => "boolean"
    ];

    public function mwcnu()
    {
        return $this->belongsTo(Mwcnu::class);
    }
}
