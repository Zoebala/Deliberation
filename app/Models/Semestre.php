<?php

namespace App\Models;

use App\Models\Cours;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Semestre extends Model
{
    use HasFactory;
    protected $fillable=["lib","annee_id","created_at","updated_at"];

    public function cours():HasMany
    {
        return $this->hasMany(Cours::class);
    }
}
