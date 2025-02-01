<?php

namespace App\Models;

use App\Traits\Query;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permsission extends Model {
    
    use Query;
    protected $fillable = [
        'name',
        'publish'
    ];

    protected $table = 'permsissions';

    public function roles() : BelongsToMany {
        return $this->belongsToMany(Role::class, 'role_permsission')->withTimestamps();
    }
}
