<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Uf extends Model
{
    use HasFactory;

    public $fillable = ['name'];
    public $timestamps = false;

    public function municipios(): BelongsToMany
    {
        return $this->belongsToMany(Municipio::class);
    }
}
