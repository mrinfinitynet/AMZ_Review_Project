<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Services\ClaudeApiService;

class AdminRepository extends BaseRepository
{
    protected $resourceName = 'admins';

    public function __construct(ClaudeApiService $claudeApi)
    {
        parent::__construct($claudeApi);
        $this->model = Admin::class;
    }

    /**
     * Apply search query for admins
     */
    protected function applySearchQuery($query, $searchTerm)
    {
        return $query->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('phone', 'like', "%{$searchTerm}%");
    }

    /**
     * Get admins with pagination
     */
    public function paginate($perPage = 15)
    {
        if ($this->useApi()) {
            return $this->claudeApi->getAll($this->resourceName, ['per_page' => $perPage]);
        }

        return Admin::with(['projects', 'accounts', 'posts'])
            ->latest()
            ->paginate($perPage);
    }
}
