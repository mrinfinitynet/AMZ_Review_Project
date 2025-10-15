<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AmazonReviewProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AmazonReviewProjectApiController extends Controller
{
    /**
     * Get all projects with optional filtering
     * GET /api/amazon-review-projects
     *
     * Query parameters: any field name (project_id, type, status, book_asin, etc.)
     * Example: /api/amazon-review-projects?type=Client1&status=pending
     */
    public function index(Request $request)
    {
        try {
            $query = AmazonReviewProject::query();

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
                $projects = $query->get();
                return response()->json([
                    'success' => true,
                    'data' => $projects,
                    'total' => $projects->count()
                ]);
            }

            $projects = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $projects->items(),
                'pagination' => [
                    'total' => $projects->total(),
                    'per_page' => $projects->perPage(),
                    'current_page' => $projects->currentPage(),
                    'last_page' => $projects->lastPage(),
                    'from' => $projects->firstItem(),
                    'to' => $projects->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single project by ID
     * GET /api/amazon-review-projects/{id}
     */
    public function show($id)
    {
        try {
            $project = AmazonReviewProject::find($id);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $project
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get projects grouped by project_id
     * GET /api/amazon-review-projects/grouped
     *
     * Query parameters: type (required)
     */
    public function grouped(Request $request)
    {
        try {
            $type = $request->input('type');

            if (!$type) {
                return response()->json([
                    'success' => false,
                    'message' => 'Type parameter is required'
                ], 422);
            }

            $projectsByGroup = AmazonReviewProject::where('type', $type)
                ->orderBy('project_id', 'DESC')
                ->get()
                ->groupBy('project_id');

            $results = $projectsByGroup->map(function($reviews, $projectId) {
                return [
                    'project_id' => $projectId,
                    'book_asin' => $reviews->first()->book_asin ?? null,
                    'review_link' => $reviews->first()->review_link ?? null,
                    'created_at' => $reviews->first()->created_at,
                    'updated_at' => $reviews->first()->updated_at,
                    'reviews' => $reviews
                ];
            })->values();

            return response()->json([
                'success' => true,
                'data' => $results,
                'total' => $results->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve grouped projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new project
     * POST /api/amazon-review-projects
     *
     * Body: {
     *   "type": "string",
     *   "project_id": "string",
     *   "book_asin": "string",
     *   "account_id": "string",
     *   "review_title": "string",
     *   "review_description": "string",
     *   "rating": 5,
     *   "status": "pending"
     * }
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'nullable|string|max:255',
                'project_id' => 'nullable|string|max:255',
                'book_asin' => 'nullable|string|max:255',
                'review_link' => 'nullable|string',
                'account_id' => 'nullable|string|max:255',
                'review_title' => 'nullable|string',
                'review_description' => 'nullable|string',
                'rating' => 'nullable|integer|min:1|max:5',
                'status' => 'nullable|in:pending,approved,rejected,delete'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $project = AmazonReviewProject::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Project created successfully',
                'data' => $project
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update project
     * PUT/PATCH /api/amazon-review-projects/{id}
     *
     * Body: any fields to update
     */
    public function update(Request $request, $id)
    {
        try {
            $project = AmazonReviewProject::find($id);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'type' => 'nullable|string|max:255',
                'project_id' => 'nullable|string|max:255',
                'book_asin' => 'nullable|string|max:255',
                'review_link' => 'nullable|string',
                'account_id' => 'nullable|string|max:255',
                'review_title' => 'nullable|string',
                'review_description' => 'nullable|string',
                'rating' => 'nullable|integer|min:1|max:5',
                'status' => 'nullable|in:pending,approved,rejected,delete'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $project->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully',
                'data' => $project->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete project
     * DELETE /api/amazon-review-projects/{id}
     */
    public function destroy($id)
    {
        try {
            $project = AmazonReviewProject::find($id);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project not found'
                ], 404);
            }

            $project->delete();

            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update project status by project_id
     * POST /api/amazon-review-projects/update-status
     *
     * Body: { "project_id": "123", "status": "delete" }
     */
    public function updateStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_id' => 'required|string',
                'status' => 'required|in:pending,approved,rejected,delete'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updated = AmazonReviewProject::where('project_id', $request->project_id)
                ->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => "Successfully updated {$updated} projects"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update project status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete projects
     * POST /api/amazon-review-projects/bulk-delete
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

            $deleted = AmazonReviewProject::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deleted} projects"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update projects
     * POST /api/amazon-review-projects/bulk-update
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

            $updated = AmazonReviewProject::whereIn('id', $request->ids)
                ->update($request->data);

            return response()->json([
                'success' => true,
                'message' => "Successfully updated {$updated} projects"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
