<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AmazonReviewProjectHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AmazonReviewProjectHistoryApiController extends Controller
{
    /**
     * Get all history records with optional filtering
     * GET /api/amazon-review-project-histories
     *
     * Query parameters: any field name (review_id, project_id, type, status, etc.)
     * Example: /api/amazon-review-project-histories?type=Client1&status=approved
     */
    public function index(Request $request)
    {
        try {
            $query = AmazonReviewProjectHistory::query();

            // Apply filters dynamically based on request parameters
            $excludedParams = ['per_page', 'page', 'no_pagination', 'order_by', 'order_direction'];

            foreach ($request->all() as $key => $value) {
                if ($value !== null && $value !== '' && !in_array($key, $excludedParams)) {
                    // Support both exact match and LIKE search
                    if ($request->has($key . '_exact')) {
                        $query->where($key, $value);
                    } else {
                        $query->where($key, 'like', "%{$value}%");
                    }
                }
            }

            // Ordering support
            $orderBy = $request->input('order_by', 'id');
            $orderDirection = $request->input('order_direction', 'desc');
            $query->orderBy($orderBy, $orderDirection);

            // Pagination support
            $perPage = $request->input('per_page', 15);

            if ($request->has('no_pagination')) {
                $histories = $query->get();
                return response()->json([
                    'success' => true,
                    'data' => $histories,
                    'total' => $histories->count()
                ]);
            }

            $histories = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $histories->items(),
                'pagination' => [
                    'total' => $histories->total(),
                    'per_page' => $histories->perPage(),
                    'current_page' => $histories->currentPage(),
                    'last_page' => $histories->lastPage(),
                    'from' => $histories->firstItem(),
                    'to' => $histories->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve history records',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single history record by ID
     * GET /api/amazon-review-project-histories/{id}
     */
    public function show($id)
    {
        try {
            $history = AmazonReviewProjectHistory::find($id);

            if (!$history) {
                return response()->json([
                    'success' => false,
                    'message' => 'History record not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $history
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve history record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new history record
     * POST /api/amazon-review-project-histories
     *
     * Body: {
     *   "review_id": 1,
     *   "project_id": "string",
     *   "account_id": "string",
     *   "rating": 5,
     *   "msg": "string",
     *   "status": "approved",
     *   "type": "Client1"
     * }
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'review_id' => 'nullable|integer',
                'project_id' => 'nullable|string|max:255',
                'account_id' => 'nullable|string|max:255',
                'rating' => 'nullable|integer|min:1|max:5',
                'msg' => 'nullable|string',
                'status' => 'nullable|in:pending,approved,rejected,delete',
                'type' => 'nullable|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $history = AmazonReviewProjectHistory::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'History record created successfully',
                'data' => $history
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create history record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update history record
     * PUT/PATCH /api/amazon-review-project-histories/{id}
     *
     * Body: any fields to update
     */
    public function update(Request $request, $id)
    {
        try {
            $history = AmazonReviewProjectHistory::find($id);

            if (!$history) {
                return response()->json([
                    'success' => false,
                    'message' => 'History record not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'review_id' => 'nullable|integer',
                'project_id' => 'nullable|string|max:255',
                'account_id' => 'nullable|string|max:255',
                'rating' => 'nullable|integer|min:1|max:5',
                'msg' => 'nullable|string',
                'status' => 'nullable|in:pending,approved,rejected,delete',
                'type' => 'nullable|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $history->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'History record updated successfully',
                'data' => $history->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update history record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete history record
     * DELETE /api/amazon-review-project-histories/{id}
     */
    public function destroy($id)
    {
        try {
            $history = AmazonReviewProjectHistory::find($id);

            if (!$history) {
                return response()->json([
                    'success' => false,
                    'message' => 'History record not found'
                ], 404);
            }

            $history->delete();

            return response()->json([
                'success' => true,
                'message' => 'History record deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete history record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear history by type
     * POST /api/amazon-review-project-histories/clear
     *
     * Body: { "type": "Client1" }
     */
    public function clearHistory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $deleted = AmazonReviewProjectHistory::where('type', $request->type)->delete();

            return response()->json([
                'success' => true,
                'message' => "Successfully cleared {$deleted} history records for type {$request->type}"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete history records
     * POST /api/amazon-review-project-histories/bulk-delete
     *
     * Body: { "ids": [1, 2, 3] }
     */
    public function bulkDelete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ids' => 'required|array',
                'ids.*' => 'integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $deleted = AmazonReviewProjectHistory::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deleted} history records"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete history records',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update history records
     * POST /api/amazon-review-project-histories/bulk-update
     *
     * Body: {
     *   "ids": [1, 2, 3],
     *   "data": { "status": "approved" }
     * }
     */
    public function bulkUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ids' => 'required|array',
                'ids.*' => 'integer',
                'data' => 'required|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updated = AmazonReviewProjectHistory::whereIn('id', $request->ids)
                ->update($request->data);

            return response()->json([
                'success' => true,
                'message' => "Successfully updated {$updated} history records"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update history records',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
