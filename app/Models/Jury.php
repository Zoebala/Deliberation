<?php

namespace App\Models;

use App\Models\Annee;
use App\Models\Section;
use App\Models\Membrejury;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jury extends Model
{
    use HasFactory;
    protected $fillable=["lib","section_id","annee_id","created_at","updated_at"];

    public function section():BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
    public function annee():BelongsTo
    {
        return $this->belongsTo(Annee::class);
    }

    public function membrejury():HasMany
    {
        return $this->hasMany(Membrejury::class);
    }
}
