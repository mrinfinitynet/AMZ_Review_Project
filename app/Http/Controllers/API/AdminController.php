<?php

namespace App\Http\Controllers\API;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;

class AdminController extends BaseApiController
{
    /**
     * Get all admins
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $admins = Admin::with(['projects', 'accounts', 'posts'])
                ->latest()
                ->paginate($perPage);

            return $this->paginatedResponse($admins, 'Admins retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Create a new admin
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:admins,email',
                'password' => 'required|min:8',
                'phone' => 'nullable|string|max:20',
                'role' => 'nullable|in:admin,super_admin,moderator',
                'status' => 'nullable|in:active,inactive,suspended',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation error', 422, $validator->errors());
            }

            $data = $request->all();
            $data['password'] = Hash::make($request->password);

            $admin = Admin::create($data);

            return $this->successResponse($admin, 'Admin created successfully', 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Get a single admin
     */
    public function show($id)
    {
        try {
            $admin = Admin::with(['projects', 'accounts', 'posts'])->findOrFail($id);

            return $this->successResponse($admin, 'Admin retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    /**
     * Update an admin
     */
    public function update(Request $request, $id)
    {
        try {
            $admin = Admin::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:admins,email,' . $id,
                'password' => 'sometimes|min:8',
                'phone' => 'nullable|string|max:20',
                'role' => 'sometimes|in:admin,super_admin,moderator',
                'status' => 'sometimes|in:active,inactive,suspended',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation error', 422, $validator->errors());
            }

            $data = $request->all();
            if (isset($data['password'])) {
                $data['password'] = Hash::make($request->password);
            }

            $admin->update($data);

            return $this->successResponse($admin->fresh(), 'Admin updated successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Delete an admin
     */
    public function destroy($id)
    {
        try {
            $admin = Admin::findOrFail($id);
            $admin->delete();

            return $this->successResponse(null, 'Admin deleted successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Search admins
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('query', '');
            $perPage = $request->get('per_page', 15);

            $admins = Admin::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('phone', 'like', "%{$query}%")
                ->latest()
                ->paginate($perPage);

            return $this->paginatedResponse($admins, 'Search results retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
