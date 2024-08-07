<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actualite extends Model
{
    use HasFactory;
    protected $fillable=["objet","photo","description","created_at","updated_at"];

    protected $casts=["created_at"=>'datetime'];
}
