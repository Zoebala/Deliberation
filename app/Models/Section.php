<?php

namespace App\Models;

use App\Models\Jury;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    protected $fillable=["lib","description","created_at","updated_at"];

    public function jury():HasMany
    {
        return $this->hasMany(Jury::class);
    }
}
