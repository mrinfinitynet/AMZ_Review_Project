<?php

namespace App\Http\Controllers\API;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class AccountController extends BaseApiController
{
    /**
     * Get all accounts
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $accounts = Account::with(['project', 'admin', 'posts'])
                ->latest()
                ->paginate($perPage);

            return $this->paginatedResponse($accounts, 'Accounts retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Create a new account
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255',
                'email' => 'nullable|email',
                'platform' => 'required|string|max:255',
                'platform_id' => 'nullable|string',
                'project_id' => 'nullable|exists:projects,id',
                'admin_id' => 'nullable|exists:admins,id',
                'status' => 'nullable|in:active,inactive,suspended,banned',
                'access_token' => 'nullable|string',
                'refresh_token' => 'nullable|string',
                'token_expires_at' => 'nullable|date',
                'followers_count' => 'nullable|integer|min:0',
                'following_count' => 'nullable|integer|min:0',
                'metadata' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation error', 422, $validator->errors());
            }

            $account = Account::create($request->all());

            return $this->successResponse($account->load(['project', 'admin', 'posts']), 'Account created successfully', 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Get a single account
     */
    public function show($id)
    {
        try {
            $account = Account::with(['project', 'admin', 'posts'])->findOrFail($id);

            return $this->successResponse($account, 'Account retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    /**
     * Update an account
     */
    public function update(Request $request, $id)
    {
        try {
            $account = Account::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'username' => 'sometimes|string|max:255',
                'email' => 'nullable|email',
                'platform' => 'sometimes|string|max:255',
                'platform_id' => 'nullable|string',
                'project_id' => 'nullable|exists:projects,id',
                'admin_id' => 'nullable|exists:admins,id',
                'status' => 'sometimes|in:active,inactive,suspended,banned',
                'access_token' => 'nullable|string',
                'refresh_token' => 'nullable|string',
                'token_expires_at' => 'nullable|date',
                'followers_count' => 'nullable|integer|min:0',
                'following_count' => 'nullable|integer|min:0',
                'metadata' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation error', 422, $validator->errors());
            }

            $account->update($request->all());

            return $this->successResponse($account->fresh()->load(['project', 'admin', 'posts']), 'Account updated successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Delete an account
     */
    public function destroy($id)
    {
        try {
            $account = Account::findOrFail($id);
            $account->delete();

            return $this->successResponse(null, 'Account deleted successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Search accounts
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('query', '');
            $perPage = $request->get('per_page', 15);

            $accounts = Account::where('username', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('platform', 'like', "%{$query}%")
                ->latest()
                ->paginate($perPage);

            return $this->paginatedResponse($accounts, 'Search results retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
