<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suivre extends Model
{
    use HasFactory;
    protected $fillable=["periodeD","periodeF","tj","examen","created_at","updated_at"];

    
}
