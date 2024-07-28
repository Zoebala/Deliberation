<?php

namespace App\Models;

use App\Models\Annee;
use App\Models\Etudiant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable=["annee_id","etudiant_id"];

    public function annee():BelongsTo
    {
        return $this->belongsTo(Annee::class);
    }
    public function etudiant():BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }
}
