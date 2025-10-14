<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskExecution extends Model
{
    use HasFactory;

    protected $table = 'task_execution';

    protected $fillable = [
        'review_id',
        'task_type',
        'status',
        'progress',
        'message',
        'worker_id',
        'worker_ip',
        'started_at',
        'completed_at',
        'error_message',
        'screenshot_path',
        'attempts',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress' => 'integer',
        'attempts' => 'integer',
    ];

    /**
     * Get the review associated with this task
     */
    public function review()
    {
        return $this->belongsTo(AmazonReviewProject::class, 'review_id');
    }

    /**
     * Get the worker handling this task
     */
    public function worker()
    {
        return $this->belongsTo(WorkerStatus::class, 'worker_id', 'worker_id');
    }

    /**
     * Scope for pending tasks
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for processing tasks
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    /**
     * Scope for completed tasks
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for failed tasks
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
