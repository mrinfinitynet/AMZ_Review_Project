<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'admin_id',
        'status',
        'priority',
        'start_date',
        'end_date',
        'budget',
        'metadata',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->name);
            }
        });
    }

    /**
     * Get the admin that manages this project
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the accounts associated with this project
     */
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Get the posts associated with this project
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Check if project is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if project is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
