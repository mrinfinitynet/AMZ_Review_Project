<?php

namespace App\Repositories;

use App\Models\Project;
use App\Services\ClaudeApiService;

class ProjectRepository extends BaseRepository
{
    protected $resourceName = 'projects';

    public function __construct(ClaudeApiService $claudeApi)
    {
        parent::__construct($claudeApi);
        $this->model = Project::class;
    }

    /**
     * Apply search query for projects
     */
    protected function applySearchQuery($query, $searchTerm)
    {
        return $query->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhere('slug', 'like', "%{$searchTerm}%");
    }

    /**
     * Get projects with pagination
     */
    public function paginate($perPage = 15)
    {
        if ($this->useApi()) {
            return $this->claudeApi->getAll($this->resourceName, ['per_page' => $perPage]);
        }

        return Project::with(['admin', 'accounts', 'posts'])
            ->latest()
            ->paginate($perPage);
    }
}
