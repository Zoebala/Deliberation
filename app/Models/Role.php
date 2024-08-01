<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as ModelRole;
use Illuminate\Database\Eloquent\Model;

class Role extends ModelRole
{
    use HasFactory;
}
