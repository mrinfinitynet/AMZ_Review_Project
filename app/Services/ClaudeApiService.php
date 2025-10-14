<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ClaudeApiService
{
    protected $baseUrl;
    protected $apiToken;
    protected $timeout;
    protected $enabled;

    public function __construct()
    {
        $this->baseUrl = config('claude.url');
        $this->apiToken = config('claude.api_token');
        $this->timeout = config('claude.timeout', 30);
        $this->enabled = config('claude.enabled', false);
    }

    /**
     * Check if API mode is enabled
     */
    public function isEnabled(): bool
    {
        return $this->enabled && !empty($this->baseUrl);
    }

    /**
     * Make a GET request to the Claude API
     */
    public function get(string $endpoint, array $params = [])
    {
        return $this->request('GET', $endpoint, ['query' => $params]);
    }

    /**
     * Make a POST request to the Claude API
     */
    public function post(string $endpoint, array $data = [])
    {
        return $this->request('POST', $endpoint, ['json' => $data]);
    }

    /**
     * Make a PUT request to the Claude API
     */
    public function put(string $endpoint, array $data = [])
    {
        return $this->request('PUT', $endpoint, ['json' => $data]);
    }

    /**
     * Make a DELETE request to the Claude API
     */
    public function delete(string $endpoint, array $params = [])
    {
        return $this->request('DELETE', $endpoint, ['query' => $params]);
    }

    /**
     * Make a PATCH request to the Claude API
     */
    public function patch(string $endpoint, array $data = [])
    {
        return $this->request('PATCH', $endpoint, ['json' => $data]);
    }

    /**
     * Generic request method
     */
    protected function request(string $method, string $endpoint, array $options = [])
    {
        if (!$this->isEnabled()) {
            throw new Exception('Claude API is not enabled. Please set CLAUDE_URL in your .env file.');
        }

        try {
            $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->timeout($this->timeout)
            ->{strtolower($method)}($url, $options['json'] ?? $options['query'] ?? []);

            if ($response->successful()) {
                return $response->json();
            }

            // Log error for debugging
            Log::error('Claude API Error', [
                'method' => $method,
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            throw new Exception('API request failed: ' . $response->body(), $response->status());

        } catch (Exception $e) {
            Log::error('Claude API Exception', [
                'method' => $method,
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get all records from a resource
     */
    public function getAll(string $resource, array $params = [])
    {
        $endpoint = config("claude.endpoints.{$resource}");
        return $this->get($endpoint, $params);
    }

    /**
     * Get a single record by ID
     */
    public function getById(string $resource, int $id)
    {
        $endpoint = config("claude.endpoints.{$resource}") . '/' . $id;
        return $this->get($endpoint);
    }

    /**
     * Create a new record
     */
    public function create(string $resource, array $data)
    {
        $endpoint = config("claude.endpoints.{$resource}");
        return $this->post($endpoint, $data);
    }

    /**
     * Update a record
     */
    public function update(string $resource, int $id, array $data)
    {
        $endpoint = config("claude.endpoints.{$resource}") . '/' . $id;
        return $this->put($endpoint, $data);
    }

    /**
     * Delete a record
     */
    public function destroy(string $resource, int $id)
    {
        $endpoint = config("claude.endpoints.{$resource}") . '/' . $id;
        return $this->delete($endpoint);
    }

    /**
     * Search records
     */
    public function search(string $resource, array $criteria)
    {
        $endpoint = config("claude.endpoints.{$resource}") . '/search';
        return $this->post($endpoint, $criteria);
    }
}
