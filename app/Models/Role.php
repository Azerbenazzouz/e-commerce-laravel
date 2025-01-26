<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {
    
    protected $fillable = [
        'name',
        'slug',
        'publish'
    ];

    protected $table = 'roles';
}
