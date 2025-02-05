<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCatalogue extends Model {

    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'canonical',
        'description',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'image',
        'icon',
        'album',
        'publish',
        'order',
        'lft',
        'rgt',
        'level',
        'parent_id',
    ];

    protected $table = 'post_catalogue';

    public function users(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
