<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'username',
        'email',
        'platform',
        'platform_id',
        'project_id',
        'admin_id',
        'status',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'followers_count',
        'following_count',
        'metadata',
        'last_sync_at',
    ];

    protected $hidden = [
        'access_token',
        'refresh_token',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'last_sync_at' => 'datetime',
        'metadata' => 'array',
        'followers_count' => 'integer',
        'following_count' => 'integer',
    ];

    /**
     * Get the project this account belongs to
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the admin managing this account
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the posts from this account
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Check if account is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if token is expired
     */
    public function isTokenExpired(): bool
    {
        return $this->token_expires_at && $this->token_expires_at->isPast();
    }

    /**
     * Get platform display name
     */
    public function getPlatformDisplayName(): string
    {
        return ucfirst($this->platform);
    }
}
