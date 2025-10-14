<?php

namespace App\Repositories;

use App\Models\Post;
use App\Services\ClaudeApiService;

class PostRepository extends BaseRepository
{
    protected $resourceName = 'posts';

    public function __construct(ClaudeApiService $claudeApi)
    {
        parent::__construct($claudeApi);
        $this->model = Post::class;
    }

    /**
     * Apply search query for posts
     */
    protected function applySearchQuery($query, $searchTerm)
    {
        return $query->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('content', 'like', "%{$searchTerm}%");
    }

    /**
     * Get posts with pagination
     */
    public function paginate($perPage = 15)
    {
        if ($this->useApi()) {
            return $this->claudeApi->getAll($this->resourceName, ['per_page' => $perPage]);
        }

        return Post::with(['account', 'project', 'admin'])
            ->latest()
            ->paginate($perPage);
    }
}
