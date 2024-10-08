<?php

namespace App\Models;

use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Semestre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cours extends Model
{
    use HasFactory;

    protected $fillable=["lib","ponderation","classe_id","semestre_id","created_at","updated_at"];

    public function classe():BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }
    public function semestre():BelongsTo
    {
        return $this->belongsTo(Semestre::class);
    }


}
