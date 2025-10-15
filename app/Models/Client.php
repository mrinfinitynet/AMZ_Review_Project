<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'key',
        'description',
        'is_active',
        'sort_order',
        'last_accessed_at',
        'access_count'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'access_count' => 'integer',
        'last_accessed_at' => 'datetime'
    ];

    /**
     * Get all active clients ordered by sort_order
     */
    public static function getActive()
    {
        return self::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Generate a unique access key in format XXXX-XXXX-XXXX
     */
    public static function generateKey()
    {
        do {
            $key = strtoupper(Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4));
        } while (self::where('key', $key)->exists());

        return $key;
    }

    /**
     * Check if the client's access key is valid (active and has key)
     */
    public function isValid()
    {
        return $this->is_active && !empty($this->key);
    }

    /**
     * Track access (increment count and update timestamp)
     */
    public function trackAccess()
    {
        $this->increment('access_count');
        $this->update(['last_accessed_at' => now()]);
    }

    /**
     * Get projects belonging to this client
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'type', 'code');
    }
}
