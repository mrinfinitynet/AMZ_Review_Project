<?php

namespace App\Repositories;

use App\Services\ClaudeApiService;
use Exception;

abstract class BaseRepository
{
    protected $claudeApi;
    protected $model;
    protected $resourceName;

    public function __construct(ClaudeApiService $claudeApi)
    {
        $this->claudeApi = $claudeApi;
    }

    /**
     * Check if we should use API or local database
     */
    protected function useApi(): bool
    {
        return $this->claudeApi->isEnabled();
    }

    /**
     * Get all records
     */
    public function all(array $params = [])
    {
        if ($this->useApi()) {
            return $this->claudeApi->getAll($this->resourceName, $params);
        }

        return $this->model::all();
    }

    /**
     * Find record by ID
     */
    public function find($id)
    {
        if ($this->useApi()) {
            return $this->claudeApi->getById($this->resourceName, $id);
        }

        return $this->model::find($id);
    }

    /**
     * Create new record
     */
    public function create(array $data)
    {
        if ($this->useApi()) {
            return $this->claudeApi->create($this->resourceName, $data);
        }

        return $this->model::create($data);
    }

    /**
     * Update record
     */
    public function update($id, array $data)
    {
        if ($this->useApi()) {
            return $this->claudeApi->update($this->resourceName, $id, $data);
        }

        $record = $this->model::findOrFail($id);
        $record->update($data);
        return $record;
    }

    /**
     * Delete record
     */
    public function delete($id)
    {
        if ($this->useApi()) {
            return $this->claudeApi->destroy($this->resourceName, $id);
        }

        $record = $this->model::findOrFail($id);
        return $record->delete();
    }

    /**
     * Search records
     */
    public function search(array $criteria)
    {
        if ($this->useApi()) {
            return $this->claudeApi->search($this->resourceName, $criteria);
        }

        // Implement local search logic
        $query = $this->model::query();

        if (isset($criteria['query'])) {
            // Override this method in child repositories for custom search
            $query = $this->applySearchQuery($query, $criteria['query']);
        }

        return $query->get();
    }

    /**
     * Apply search query - override in child repositories
     */
    protected function applySearchQuery($query, $searchTerm)
    {
        return $query;
    }
}
