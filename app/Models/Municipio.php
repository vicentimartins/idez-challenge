<?php

namespace App\Models;

use App\Models\Uf;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Municipio extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;
    public $fillable = ['name', 'ibge_code'];

    public function ufs(): BelongsToMany
    {
        return $this->belongsToMany(Uf::class);
    }
}
