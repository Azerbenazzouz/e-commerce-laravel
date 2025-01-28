<?php

namespace App\Models;

use App\Traits\Query;
use Illuminate\Database\Eloquent\Model;

class Role extends Model {
    
    use Query;
    protected $fillable = [
        'name',
        'slug',
        'publish'
    ];

    protected $table = 'roles';
}
