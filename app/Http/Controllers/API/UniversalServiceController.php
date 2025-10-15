<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class UniversalServiceController extends Controller
{
    /**
     * Base URL for external Claude API
     */
    protected $baseUrl;

    /**
     * Available tables/endpoints
     */
    protected $allowedTables = [
        'amazon_review_accounts',
        'amazon_review_projects',
        'amazon_review_project_histories',
        'clients',
    ];

    public function __construct()
    {
        // Get base URL from environment or config
        $this->baseUrl = env('CLAUDE_API_BASE_URL', 'http://your-claude-api.com/api');
    }

    /**
     * Get all records from external API
     * GET /api/service/{table}
     */
    public function index(Request $request, $table)
    {
        try {
            if (!$this->isTableAllowed($table)) {
                return $this->error('Table not found or not allowed', 404);
            }

            // Build query parameters
            $params = $request->all();

            // Make GET request to external API
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get("{$this->baseUrl}/{$table}", $params);

            return $this->handleResponse($response);

        } catch (\Exception $e) {
            return $this->error('Failed to retrieve records: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get single record from external API
     * GET /api/service/{table}/{id}
     */
    public function show(Request $request, $table, $id)
    {
        try {
            if (!$this->isTableAllowed($table)) {
                return $this->error('Table not found or not allowed', 404);
            }

            $params = $request->only(['fields']);

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get("{$this->baseUrl}/{$table}/{$id}", $params);

            return $this->handleResponse($response);

        } catch (\Exception $e) {
            return $this->error('Failed to retrieve record: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Create new record via external API
     * POST /api/service/{table}
     */
    public function store(Request $request, $table)
    {
        try {
            if (!$this->isTableAllowed($table)) {
                return $this->error('Table not found or not allowed', 404);
            }

            $data = $request->all();

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->post("{$this->baseUrl}/{$table}", $data);

            return $this->handleResponse($response);

        } catch (\Exception $e) {
            return $this->error('Failed to create record: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update record via external API
     * PUT/PATCH /api/service/{table}/{id}
     */
    public function update(Request $request, $table, $id)
    {
        try {
            if (!$this->isTableAllowed($table)) {
                return $this->error('Table not found or not allowed', 404);
            }

            $data = $request->all();

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->put("{$this->baseUrl}/{$table}/{$id}", $data);

            return $this->handleResponse($response);

        } catch (\Exception $e) {
            return $this->error('Failed to update record: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Delete record via external API
     * DELETE /api/service/{table}/{id}
     */
    public function destroy($table, $id)
    {
        try {
            if (!$this->isTableAllowed($table)) {
                return $this->error('Table not found or not allowed', 404);
            }

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->delete("{$this->baseUrl}/{$table}/{$id}");

            return $this->handleResponse($response);

        } catch (\Exception $e) {
            return $this->error('Failed to delete record: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk delete via external API
     * POST /api/service/{table}/bulk-delete
     */
    public function bulkDelete(Request $request, $table)
    {
        try {
            if (!$this->isTableAllowed($table)) {
                return $this->error('Table not found or not allowed', 404);
            }

            $validator = Validator::make($request->all(), [
                'ids' => 'required|array',
                'ids.*' => 'integer'
            ]);

            if ($validator->fails()) {
                return $this->error('Validation failed', 422, $validator->errors());
            }

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->post("{$this->baseUrl}/{$table}/bulk-delete", [
                    'ids' => $request->ids
                ]);

            return $this->handleResponse($response);

        } catch (\Exception $e) {
            return $this->error('Failed to delete records: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Bulk update via external API
     * POST /api/service/{table}/bulk-update
     */
    public function bulkUpdate(Request $request, $table)
    {
        try {
            if (!$this->isTableAllowed($table)) {
                return $this->error('Table not found or not allowed', 404);
            }

            $validator = Validator::make($request->all(), [
                'ids' => 'required|array',
                'ids.*' => 'integer',
                'data' => 'required|array'
            ]);

            if ($validator->fails()) {
                return $this->error('Validation failed', 422, $validator->errors());
            }

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->post("{$this->baseUrl}/{$table}/bulk-update", [
                    'ids' => $request->ids,
                    'data' => $request->data
                ]);

            return $this->handleResponse($response);

        } catch (\Exception $e) {
            return $this->error('Failed to update records: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get table structure from external API
     * GET /api/service/{table}/structure
     */
    public function structure($table)
    {
        try {
            if (!$this->isTableAllowed($table)) {
                return $this->error('Table not found or not allowed', 404);
            }

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get("{$this->baseUrl}/{$table}/structure");

            return $this->handleResponse($response);

        } catch (\Exception $e) {
            return $this->error('Failed to get table structure: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Execute custom query via external API
     * POST /api/service/{table}/query
     */
    public function customQuery(Request $request, $table)
    {
        try {
            if (!$this->isTableAllowed($table)) {
                return $this->error('Table not found or not allowed', 404);
            }

            $validator = Validator::make($request->all(), [
                'conditions' => 'required|array',
                'conditions.*.field' => 'required|string',
                'conditions.*.operator' => 'required|string',
                'conditions.*.value' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->error('Validation failed', 422, $validator->errors());
            }

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->post("{$this->baseUrl}/{$table}/query", $request->all());

            return $this->handleResponse($response);

        } catch (\Exception $e) {
            return $this->error('Failed to execute query: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get record count from external API
     * GET /api/service/{table}/count
     */
    public function count(Request $request, $table)
    {
        try {
            if (!$this->isTableAllowed($table)) {
                return $this->error('Table not found or not allowed', 404);
            }

            $params = $request->all();

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get("{$this->baseUrl}/{$table}/count", $params);

            return $this->handleResponse($response);

        } catch (\Exception $e) {
            return $this->error('Failed to get count: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get available tables from external API
     * GET /api/service/tables
     */
    public function tables()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get("{$this->baseUrl}/tables");

            return $this->handleResponse($response);

        } catch (\Exception $e) {
            return $this->error('Failed to get tables: ' . $e->getMessage(), 500);
        }
    }

    // ==================== Helper Methods ====================

    /**
     * Check if table is allowed
     */
    protected function isTableAllowed($table)
    {
        return in_array($table, $this->allowedTables);
    }

    /**
     * Get HTTP headers for API requests
     */
    protected function getHeaders()
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Handle external API response
     */
    protected function handleResponse($response)
    {
        // Check if request was successful
        if ($response->successful()) {
            // Return the JSON response as-is
            return response()->json($response->json(), $response->status());
        }

        // Handle errors
        if ($response->failed()) {
            $errorData = $response->json();

            return response()->json([
                'success' => false,
                'message' => $errorData['message'] ?? 'API request failed',
                'error' => $errorData['error'] ?? 'Unknown error',
                'errors' => $errorData['errors'] ?? null
            ], $response->status());
        }

        // Fallback error
        return $this->error('Unexpected API response', 500);
    }

    /**
     * Error response
     */
    protected function error($message, $statusCode = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Get current API configuration (for debugging)
     * GET /api/service/config
     */
    public function config()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'base_url' => $this->baseUrl,
                'allowed_tables' => $this->allowedTables,
                'timeout' => 30,
                'status' => 'ready'
            ]
        ]);
    }

    /**
     * Test API connection
     * GET /api/service/test-connection
     */
    public function testConnection()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(10)
                ->get("{$this->baseUrl}/tables");

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Connection successful',
                    'data' => [
                        'base_url' => $this->baseUrl,
                        'status_code' => $response->status(),
                        'response_time' => $response->transferStats ? $response->transferStats->getTransferTime() : null
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Connection failed',
                'error' => 'API returned status: ' . $response->status()
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
