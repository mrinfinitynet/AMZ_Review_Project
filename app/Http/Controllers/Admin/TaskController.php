<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AmazonReviewProject;
use App\Models\TaskExecution;
use App\Models\WorkerStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Start a review task and send it to the worker
     */
    public function startReview(Request $request)
    {
        $reviewId = $request->input('review_id');

        // 1. Create task record
        $task = TaskExecution::create([
            'review_id' => $reviewId,
            'task_type' => 'review',
            'status' => 'pending',
            'attempts' => 0
        ]);

        // 2. Get review details
        $review = AmazonReviewProject::find($reviewId);

        if (!$review) {
            $task->update([
                'status' => 'failed',
                'error_message' => 'Review not found'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }

        // 3. Get available worker
        $workerUrl = env('WORKER_URL', 'http://localhost:3000');

        // 4. Send task to Static PC worker
        try {
            $response = Http::timeout(5)->post($workerUrl . '/execute', [
                'task_id' => $task->id,
                'review_id' => $reviewId,
                'account_id' => $review->account_id,
                'book_asin' => $review->book_asin,
                'review_title' => $review->review_title,
                'review_description' => $review->review_description,
                'rating' => $review->rating,
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                $task->update([
                    'status' => 'processing',
                    'worker_id' => $responseData['worker_id'] ?? 'unknown',
                    'started_at' => now()
                ]);

                Log::info('Task started successfully', [
                    'task_id' => $task->id,
                    'review_id' => $reviewId,
                    'worker_id' => $responseData['worker_id'] ?? 'unknown'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Task started successfully',
                    'task_id' => $task->id,
                    'status' => 'processing'
                ]);
            }

            // Worker returned error (e.g., busy)
            $task->update([
                'status' => 'failed',
                'error_message' => $response->json()['message'] ?? 'Worker rejected task'
            ]);

            return response()->json([
                'success' => false,
                'message' => $response->json()['message'] ?? 'Worker is busy with another task'
            ], 503);

        } catch (\Exception $e) {
            Log::error('Failed to connect to worker', [
                'task_id' => $task->id,
                'review_id' => $reviewId,
                'error' => $e->getMessage()
            ]);

            $task->update([
                'status' => 'failed',
                'error_message' => 'Failed to connect to worker: ' . $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to worker. Is the worker PC online?',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get task status (for refresh/reload)
     */
    public function getTaskStatus($taskId)
    {
        $task = TaskExecution::find($taskId);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'task_id' => $task->id,
            'status' => $task->status,
            'progress' => $task->progress,
            'message' => $task->message,
            'started_at' => $task->started_at,
            'completed_at' => $task->completed_at,
            'error_message' => $task->error_message,
            'updated_at' => $task->updated_at
        ]);
    }

    /**
     * Get the latest task for a review
     */
    public function getReviewTaskStatus($reviewId)
    {
        $task = TaskExecution::where('review_id', $reviewId)
            ->latest()
            ->first();

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'No task found for this review'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'task_id' => $task->id,
            'status' => $task->status,
            'progress' => $task->progress,
            'message' => $task->message,
            'started_at' => $task->started_at,
            'completed_at' => $task->completed_at,
            'error_message' => $task->error_message,
            'updated_at' => $task->updated_at
        ]);
    }

    /**
     * Retry a failed task
     */
    public function retryTask($taskId)
    {
        $task = TaskExecution::find($taskId);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        // Reset task status
        $task->update([
            'status' => 'pending',
            'error_message' => null,
            'attempts' => $task->attempts + 1,
        ]);

        // Resend to worker
        return $this->startReview(new Request(['review_id' => $task->review_id]));
    }
}
