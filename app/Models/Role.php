<?php

namespace App\Models;

use App\Traits\Query;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model {
    
    use Query;
    protected $fillable = [
        'name',
        'slug',
        'publish'
    ];

    protected $table = 'roles';

    public function users() : BelongsToMany {
        return $this->belongsToMany(User::class, 'role_user');
    }

    public function permissions() : BelongsToMany {
        return $this->belongsToMany(Permission::class, 'role_permission')->withTimestamps();
    }
}
