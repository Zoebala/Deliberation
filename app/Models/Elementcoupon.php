<?php

namespace App\Models;

use App\Models\Cours;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Elementcoupon extends Model
{
    use HasFactory;
    protected $fillable=["tj","examenS1","examenS2","coupon_id","cours_id"];

    public function coupon():BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }
    public function cours():BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }
}
