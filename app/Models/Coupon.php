<?php

namespace App\Models;

use App\Models\Annee;
use App\Models\Etudiant;
use App\Models\Elementcoupon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable=["annee_id","etudiant_id","classe_id"];

    public function annee():BelongsTo
    {
        return $this->belongsTo(Annee::class);
    }
    public function etudiant():BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function elementscoupon():HasMany
    {
        return $this->hasMany(Elementcoupon::class);
    }
}
