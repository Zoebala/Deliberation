<?php

namespace App\Models;

use App\Models\Jury;
use App\Models\Cours;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classe extends Model
{
    use HasFactory;
    protected $fillable=["lib","jury_id","created_at","updated_at"];

    public function jury():BelongsTo
    {
        return $this->belongsTo(Jury::class);
    }

    public function cours():HasMany
    {
        return $this->hasMany(Cours::class);
    }
}
