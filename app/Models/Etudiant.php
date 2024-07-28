<?php

namespace App\Models;

use App\Models\Cours;
use App\Models\Classe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Etudiant extends Model
{
    use HasFactory;
    protected $fillable=["nom","postnom","prenom","genre","classe_id","created_at","updated_at"];

    public function classe():BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }


}
