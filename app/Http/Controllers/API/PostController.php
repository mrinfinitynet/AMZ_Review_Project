<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class PostController extends BaseApiController
{
    /**
     * Get all posts
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $posts = Post::with(['account', 'project', 'admin'])
                ->latest()
                ->paginate($perPage);

            return $this->paginatedResponse($posts, 'Posts retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Create a new post
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'post_type' => 'nullable|in:text,image,video,link',
                'account_id' => 'nullable|exists:accounts,id',
                'project_id' => 'nullable|exists:projects,id',
                'admin_id' => 'nullable|exists:admins,id',
                'status' => 'nullable|in:draft,scheduled,published,failed',
                'scheduled_at' => 'nullable|date',
                'published_at' => 'nullable|date',
                'platform_post_id' => 'nullable|string',
                'post_url' => 'nullable|url',
                'likes_count' => 'nullable|integer|min:0',
                'comments_count' => 'nullable|integer|min:0',
                'shares_count' => 'nullable|integer|min:0',
                'views_count' => 'nullable|integer|min:0',
                'media' => 'nullable|array',
                'hashtags' => 'nullable|array',
                'metadata' => 'nullable|array',
                'error_message' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation error', 422, $validator->errors());
            }

            $post = Post::create($request->all());

            return $this->successResponse($post->load(['account', 'project', 'admin']), 'Post created successfully', 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Get a single post
     */
    public function show($id)
    {
        try {
            $post = Post::with(['account', 'project', 'admin'])->findOrFail($id);

            return $this->successResponse($post, 'Post retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    /**
     * Update a post
     */
    public function update(Request $request, $id)
    {
        try {
            $post = Post::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|string|max:255',
                'content' => 'sometimes|string',
                'post_type' => 'sometimes|in:text,image,video,link',
                'account_id' => 'nullable|exists:accounts,id',
                'project_id' => 'nullable|exists:projects,id',
                'admin_id' => 'nullable|exists:admins,id',
                'status' => 'sometimes|in:draft,scheduled,published,failed',
                'scheduled_at' => 'nullable|date',
                'published_at' => 'nullable|date',
                'platform_post_id' => 'nullable|string',
                'post_url' => 'nullable|url',
                'likes_count' => 'nullable|integer|min:0',
                'comments_count' => 'nullable|integer|min:0',
                'shares_count' => 'nullable|integer|min:0',
                'views_count' => 'nullable|integer|min:0',
                'media' => 'nullable|array',
                'hashtags' => 'nullable|array',
                'metadata' => 'nullable|array',
                'error_message' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation error', 422, $validator->errors());
            }

            $post->update($request->all());

            return $this->successResponse($post->fresh()->load(['account', 'project', 'admin']), 'Post updated successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Delete a post
     */
    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();

            return $this->successResponse(null, 'Post deleted successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Search posts
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('query', '');
            $perPage = $request->get('per_page', 15);

            $posts = Post::where('title', 'like', "%{$query}%")
                ->orWhere('content', 'like', "%{$query}%")
                ->latest()
                ->paginate($perPage);

            return $this->paginatedResponse($posts, 'Search results retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
