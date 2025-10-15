<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientApiController extends Controller
{
    /**
     * Get all clients with optional filtering
     * GET /api/clients
     *
     * Query parameters: any field name (name, code, is_active, etc.)
     * Example: /api/clients?is_active=1&code=Client1
     */
    public function index(Request $request)
    {
        try {
            $query = Client::query();

            // Apply filters dynamically based on request parameters
            $excludedParams = ['per_page', 'page', 'no_pagination', 'order_by', 'order_direction', 'active_only'];

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

            // Active only filter
            if ($request->has('active_only') && $request->active_only) {
                $query->where('is_active', true);
            }

            // Ordering support
            $orderBy = $request->input('order_by', 'sort_order');
            $orderDirection = $request->input('order_direction', 'asc');
            $query->orderBy($orderBy, $orderDirection);

            // Pagination support
            $perPage = $request->input('per_page', 15);

            if ($request->has('no_pagination')) {
                $clients = $query->get();
                return response()->json([
                    'success' => true,
                    'data' => $clients,
                    'total' => $clients->count()
                ]);
            }

            $clients = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $clients->items(),
                'pagination' => [
                    'total' => $clients->total(),
                    'per_page' => $clients->perPage(),
                    'current_page' => $clients->currentPage(),
                    'last_page' => $clients->lastPage(),
                    'from' => $clients->firstItem(),
                    'to' => $clients->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve clients',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active clients only
     * GET /api/clients/active
     */
    public function active()
    {
        try {
            $clients = Client::getActive();

            return response()->json([
                'success' => true,
                'data' => $clients,
                'total' => $clients->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve active clients',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single client by ID
     * GET /api/clients/{id}
     */
    public function show($id)
    {
        try {
            $client = Client::find($id);

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $client
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve client',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get client by code
     * GET /api/clients/by-code/{code}
     */
    public function showByCode($code)
    {
        try {
            $client = Client::where('code', $code)->first();

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $client
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve client',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get client by access key
     * GET /api/clients/by-key/{key}
     */
    public function showByKey($key)
    {
        try {
            $client = Client::where('key', strtoupper($key))->first();

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $client
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve client',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new client
     * POST /api/clients
     *
     * Body: {
     *   "name": "string",
     *   "code": "string",
     *   "description": "string",
     *   "is_active": true,
     *   "sort_order": 0
     * }
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:255|unique:clients,code',
                'description' => 'nullable|string',
                'is_active' => 'nullable|boolean',
                'sort_order' => 'nullable|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            $data['is_active'] = $request->input('is_active', true);
            $data['sort_order'] = $request->input('sort_order', 0);

            // Generate key if requested
            if ($request->input('generate_key', false)) {
                $data['key'] = Client::generateKey();
            }

            $client = Client::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Client created successfully',
                'data' => $client
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create client',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update client
     * PUT/PATCH /api/clients/{id}
     *
     * Body: any fields to update
     */
    public function update(Request $request, $id)
    {
        try {
            $client = Client::find($id);

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string|max:255',
                'code' => 'nullable|string|max:255|unique:clients,code,' . $id,
                'description' => 'nullable|string',
                'is_active' => 'nullable|boolean',
                'sort_order' => 'nullable|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $client->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Client updated successfully',
                'data' => $client->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update client',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete client
     * DELETE /api/clients/{id}
     */
    public function destroy($id)
    {
        try {
            $client = Client::find($id);

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }

            $client->delete();

            return response()->json([
                'success' => true,
                'message' => 'Client deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete client',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate new access key for client
     * POST /api/clients/{id}/generate-key
     */
    public function generateKey($id)
    {
        try {
            $client = Client::find($id);

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }

            $client->key = Client::generateKey();
            $client->save();

            return response()->json([
                'success' => true,
                'message' => 'Access key generated successfully',
                'data' => $client
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate access key',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove access key from client
     * POST /api/clients/{id}/remove-key
     */
    public function removeKey($id)
    {
        try {
            $client = Client::find($id);

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }

            $client->key = null;
            $client->save();

            return response()->json([
                'success' => true,
                'message' => 'Access key removed successfully',
                'data' => $client
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove access key',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle client active status
     * POST /api/clients/{id}/toggle-status
     */
    public function toggleStatus($id)
    {
        try {
            $client = Client::find($id);

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }

            $client->is_active = !$client->is_active;
            $client->save();

            return response()->json([
                'success' => true,
                'message' => 'Client status toggled successfully',
                'data' => $client
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle client status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Track access for client
     * POST /api/clients/{id}/track-access
     */
    public function trackAccess($id)
    {
        try {
            $client = Client::find($id);

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }

            $client->trackAccess();

            return response()->json([
                'success' => true,
                'message' => 'Access tracked successfully',
                'data' => $client->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to track access',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify client access key
     * POST /api/clients/verify-key
     *
     * Body: { "key": "XXXX-XXXX-XXXX" }
     */
    public function verifyKey(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'key' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $key = strtoupper(trim($request->key));
            $client = Client::where('key', $key)->first();

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid access key'
                ], 404);
            }

            if (!$client->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access key is inactive'
                ], 403);
            }

            // Track access
            $client->trackAccess();

            return response()->json([
                'success' => true,
                'message' => 'Access key verified successfully',
                'data' => $client
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify access key',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
