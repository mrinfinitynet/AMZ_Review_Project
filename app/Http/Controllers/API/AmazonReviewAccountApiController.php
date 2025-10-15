<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AmazonReviewAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AmazonReviewAccountApiController extends Controller
{
    /**
     * Get all accounts with optional filtering
     * GET /api/amazon-review-accounts
     *
     * Query parameters: any field name (account_name, account_email, type, etc.)
     * Example: /api/amazon-review-accounts?type=Client1&account_name=Test
     */
    public function index(Request $request)
    {
        try {
            $query = AmazonReviewAccount::query();

            // Apply filters dynamically based on request parameters
            foreach ($request->all() as $key => $value) {
                if ($value !== null && $value !== '') {
                    // Support both exact match and LIKE search
                    if ($request->has($key . '_exact')) {
                        $query->where($key, $value);
                    } else {
                        $query->where($key, 'like', "%{$value}%");
                    }
                }
            }

            // Pagination support
            $perPage = $request->input('per_page', 15);

            if ($request->has('no_pagination')) {
                $accounts = $query->get();
                return response()->json([
                    'success' => true,
                    'data' => $accounts,
                    'total' => $accounts->count()
                ]);
            }

            $accounts = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $accounts->items(),
                'pagination' => [
                    'total' => $accounts->total(),
                    'per_page' => $accounts->perPage(),
                    'current_page' => $accounts->currentPage(),
                    'last_page' => $accounts->lastPage(),
                    'from' => $accounts->firstItem(),
                    'to' => $accounts->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve accounts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single account by ID
     * GET /api/amazon-review-accounts/{id}
     */
    public function show($id)
    {
        try {
            $account = AmazonReviewAccount::find($id);

            if (!$account) {
                return response()->json([
                    'success' => false,
                    'message' => 'Account not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $account
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve account',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new account
     * POST /api/amazon-review-accounts
     *
     * Body: {
     *   "account_name": "string",
     *   "account_id": "string",
     *   "account_email": "string",
     *   "account_password": "string",
     *   "type": "string"
     * }
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'account_name' => 'nullable|string|max:255',
                'account_id' => 'nullable|string|max:255',
                'account_email' => 'nullable|email|max:255',
                'account_password' => 'nullable|string|max:255',
                'type' => 'nullable|string|max:255',
                'total_review' => 'nullable|integer',
                'last_checking' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $account = AmazonReviewAccount::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully',
                'data' => $account
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create account',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update account
     * PUT/PATCH /api/amazon-review-accounts/{id}
     *
     * Body: any fields to update
     */
    public function update(Request $request, $id)
    {
        try {
            $account = AmazonReviewAccount::find($id);

            if (!$account) {
                return response()->json([
                    'success' => false,
                    'message' => 'Account not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'account_name' => 'nullable|string|max:255',
                'account_id' => 'nullable|string|max:255',
                'account_email' => 'nullable|email|max:255',
                'account_password' => 'nullable|string|max:255',
                'type' => 'nullable|string|max:255',
                'total_review' => 'nullable|integer',
                'last_checking' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $account->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Account updated successfully',
                'data' => $account->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update account',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete account
     * DELETE /api/amazon-review-accounts/{id}
     */
    public function destroy($id)
    {
        try {
            $account = AmazonReviewAccount::find($id);

            if (!$account) {
                return response()->json([
                    'success' => false,
                    'message' => 'Account not found'
                ], 404);
            }

            $account->delete();

            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete account',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete accounts
     * POST /api/amazon-review-accounts/bulk-delete
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

            $deleted = AmazonReviewAccount::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deleted} accounts"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete accounts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update accounts
     * POST /api/amazon-review-accounts/bulk-update
     *
     * Body: {
     *   "ids": [1, 2, 3],
     *   "data": { "type": "Client1" }
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

            $updated = AmazonReviewAccount::whereIn('id', $request->ids)
                ->update($request->data);

            return response()->json([
                'success' => true,
                'message' => "Successfully updated {$updated} accounts"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update accounts',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
