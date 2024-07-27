<?php

namespace App\Models;

use App\Models\Cours;
use App\Models\Etudiant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recours extends Model
{
    use HasFactory;
    protected $fillable=["motif","contenu","etudiant_id","cours_id","created_at","updated_at"];

    public function etudiant():BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }
    public function cours():BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }
}
