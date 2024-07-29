<?php

namespace App\Models;

use App\Models\Jury;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Membrejury extends Model
{
    use HasFactory;
    protected $fillable=["jury_id","nom","postnom","prenom","tel","email","fonction","created_at","updated_at"];

    public function jury():BelongsTo
    {
        return $this->belongsTo(Jury::class);
    }
}
