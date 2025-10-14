<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TaskExecution;
use App\Models\WorkerStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WorkerController extends Controller
{
    /**
     * Update task progress (called by worker during execution)
     */
    public function updateProgress(Request $request, $taskId)
    {
        $task = TaskExecution::find($taskId);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        $task->update([
            'progress' => $request->input('progress', 0),
            'message' => $request->input('message', ''),
            'worker_id' => $request->input('worker_id'),
        ]);

        Log::info('Task progress updated', [
            'task_id' => $taskId,
            'progress' => $request->input('progress'),
            'message' => $request->input('message')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Progress updated'
        ]);
    }

    /**
     * Complete a task (called by worker when done)
     */
    public function completeTask(Request $request, $taskId)
    {
        $task = TaskExecution::find($taskId);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        $status = $request->input('status', 'completed');
        $message = $request->input('message', '');

        $task->update([
            'status' => $status,
            'progress' => $status === 'completed' ? 100 : $task->progress,
            'message' => $message,
            'completed_at' => now(),
            'error_message' => $status === 'failed' ? $message : null,
            'screenshot_path' => $request->input('screenshot_path'),
        ]);

        // Update worker status
        $workerId = $request->input('worker_id');
        if ($workerId) {
            $worker = WorkerStatus::where('worker_id', $workerId)->first();
            if ($worker) {
                $worker->markFree();

                if ($status === 'completed') {
                    $worker->incrementCompleted();
                } else if ($status === 'failed') {
                    $worker->incrementFailed();
                }
            }
        }

        Log::info('Task completed', [
            'task_id' => $taskId,
            'status' => $status,
            'message' => $message
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task marked as ' . $status
        ]);
    }

    /**
     * Worker heartbeat (called periodically to show worker is online)
     */
    public function heartbeat(Request $request)
    {
        $workerId = $request->input('worker_id');
        $status = $request->input('status', 'online');

        $worker = WorkerStatus::updateOrCreate(
            ['worker_id' => $workerId],
            [
                'status' => $status,
                'ip_address' => $request->ip(),
                'last_heartbeat' => now(),
                'chrome_version' => $request->input('chrome_version'),
                'current_task_id' => $request->input('current_task_id'),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Heartbeat received',
            'worker' => [
                'id' => $worker->worker_id,
                'status' => $worker->status,
                'last_heartbeat' => $worker->last_heartbeat,
            ]
        ]);
    }

    /**
     * Get worker status
     */
    public function getWorkerStatus($workerId)
    {
        $worker = WorkerStatus::where('worker_id', $workerId)->first();

        if (!$worker) {
            return response()->json([
                'success' => false,
                'message' => 'Worker not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'worker' => [
                'id' => $worker->worker_id,
                'status' => $worker->status,
                'current_task_id' => $worker->current_task_id,
                'last_heartbeat' => $worker->last_heartbeat,
                'total_completed' => $worker->total_tasks_completed,
                'total_failed' => $worker->total_tasks_failed,
            ]
        ]);
    }

    /**
     * Get all workers
     */
    public function getAllWorkers()
    {
        $workers = WorkerStatus::all();

        return response()->json([
            'success' => true,
            'workers' => $workers->map(function ($worker) {
                return [
                    'id' => $worker->worker_id,
                    'status' => $worker->status,
                    'current_task_id' => $worker->current_task_id,
                    'last_heartbeat' => $worker->last_heartbeat,
                    'total_completed' => $worker->total_tasks_completed,
                    'total_failed' => $worker->total_tasks_failed,
                    'is_online' => $worker->last_heartbeat && $worker->last_heartbeat->diffInMinutes(now()) < 5,
                ];
            })
        ]);
    }
}
