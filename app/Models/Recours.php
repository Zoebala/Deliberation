<?php

namespace App\Models;

use App\Models\Annee;
use App\Models\Cours;
use App\Models\Etudiant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recours extends Model
{
    use HasFactory;
    protected $fillable=["motif","contenu","semestre_id","etudiant_id","cours_id","classe_id","created_at","updated_at"];
    protected $casts=[
        "contenu"=>"array",
    ];

    public function etudiant():BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }
    public function annee():BelongsTo
    {
        return $this->belongsTo(Annee::class);
    }

    public function cours():BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }
}
