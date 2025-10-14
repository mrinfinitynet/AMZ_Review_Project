<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerStatus extends Model
{
    use HasFactory;

    protected $table = 'worker_status';

    protected $fillable = [
        'worker_id',
        'status',
        'current_task_id',
        'ip_address',
        'last_heartbeat',
        'chrome_version',
        'total_tasks_completed',
        'total_tasks_failed',
    ];

    protected $casts = [
        'last_heartbeat' => 'datetime',
        'total_tasks_completed' => 'integer',
        'total_tasks_failed' => 'integer',
        'current_task_id' => 'integer',
    ];

    /**
     * Get the current task being executed
     */
    public function currentTask()
    {
        return $this->belongsTo(TaskExecution::class, 'current_task_id');
    }

    /**
     * Get all tasks handled by this worker
     */
    public function tasks()
    {
        return $this->hasMany(TaskExecution::class, 'worker_id', 'worker_id');
    }

    /**
     * Scope for online workers
     */
    public function scopeOnline($query)
    {
        return $query->where('status', 'online');
    }

    /**
     * Scope for busy workers
     */
    public function scopeBusy($query)
    {
        return $query->where('status', 'busy');
    }

    /**
     * Scope for offline workers
     */
    public function scopeOffline($query)
    {
        return $query->where('status', 'offline');
    }

    /**
     * Check if worker is available (online and not busy)
     */
    public function isAvailable(): bool
    {
        return $this->status === 'online';
    }

    /**
     * Mark worker as busy with a task
     */
    public function markBusy(int $taskId): void
    {
        $this->update([
            'status' => 'busy',
            'current_task_id' => $taskId,
            'last_heartbeat' => now(),
        ]);
    }

    /**
     * Mark worker as free
     */
    public function markFree(): void
    {
        $this->update([
            'status' => 'online',
            'current_task_id' => null,
            'last_heartbeat' => now(),
        ]);
    }

    /**
     * Increment completed tasks counter
     */
    public function incrementCompleted(): void
    {
        $this->increment('total_tasks_completed');
    }

    /**
     * Increment failed tasks counter
     */
    public function incrementFailed(): void
    {
        $this->increment('total_tasks_failed');
    }
}
