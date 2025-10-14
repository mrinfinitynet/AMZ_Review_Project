<?php

namespace App\Repositories;

use App\Models\Account;
use App\Services\ClaudeApiService;

class AccountRepository extends BaseRepository
{
    protected $resourceName = 'accounts';

    public function __construct(ClaudeApiService $claudeApi)
    {
        parent::__construct($claudeApi);
        $this->model = Account::class;
    }

    /**
     * Apply search query for accounts
     */
    protected function applySearchQuery($query, $searchTerm)
    {
        return $query->where('username', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('platform', 'like', "%{$searchTerm}%");
    }

    /**
     * Get accounts with pagination
     */
    public function paginate($perPage = 15)
    {
        if ($this->useApi()) {
            return $this->claudeApi->getAll($this->resourceName, ['per_page' => $perPage]);
        }

        return Account::with(['project', 'admin', 'posts'])
            ->latest()
            ->paginate($perPage);
    }
}
