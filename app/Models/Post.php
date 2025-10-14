<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'post_type',
        'account_id',
        'project_id',
        'admin_id',
        'status',
        'scheduled_at',
        'published_at',
        'platform_post_id',
        'post_url',
        'likes_count',
        'comments_count',
        'shares_count',
        'views_count',
        'media',
        'hashtags',
        'metadata',
        'error_message',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
        'media' => 'array',
        'hashtags' => 'array',
        'metadata' => 'array',
        'likes_count' => 'integer',
        'comments_count' => 'integer',
        'shares_count' => 'integer',
        'views_count' => 'integer',
    ];

    /**
     * Get the account this post belongs to
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the project this post belongs to
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the admin who created this post
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Check if post is published
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Check if post is scheduled
     */
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    /**
     * Check if post is draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if post failed
     */
    public function hasFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get engagement rate
     */
    public function getEngagementRate(): float
    {
        if ($this->views_count === 0) {
            return 0;
        }

        $totalEngagement = $this->likes_count + $this->comments_count + $this->shares_count;
        return round(($totalEngagement / $this->views_count) * 100, 2);
    }

    /**
     * Scope for published posts
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for scheduled posts
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope for draft posts
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
