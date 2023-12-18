<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $casts = [
        'deadline' => 'datetime', 
    ];

    protected $fillable = [
        'title',
        'description',
        'deadline',
        'user_id',
    ];

    protected $hidden = ['tsvectors'];

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class);
    }

    public function coordinator() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}

