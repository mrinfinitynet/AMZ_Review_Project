<?php

namespace App\Http\Controllers\API;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;

class ProjectController extends BaseApiController
{
    /**
     * Get all projects
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $projects = Project::with(['admin', 'accounts', 'posts'])
                ->latest()
                ->paginate($perPage);

            return $this->paginatedResponse($projects, 'Projects retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Create a new project
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|unique:projects,slug',
                'description' => 'nullable|string',
                'admin_id' => 'nullable|exists:admins,id',
                'status' => 'nullable|in:active,inactive,completed,on_hold',
                'priority' => 'nullable|in:low,medium,high,urgent',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'budget' => 'nullable|numeric|min:0',
                'metadata' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation error', 422, $validator->errors());
            }

            $data = $request->all();
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($request->name);
            }

            $project = Project::create($data);

            return $this->successResponse($project->load(['admin', 'accounts', 'posts']), 'Project created successfully', 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Get a single project
     */
    public function show($id)
    {
        try {
            $project = Project::with(['admin', 'accounts', 'posts'])->findOrFail($id);

            return $this->successResponse($project, 'Project retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    /**
     * Update a project
     */
    public function update(Request $request, $id)
    {
        try {
            $project = Project::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'slug' => 'sometimes|string|unique:projects,slug,' . $id,
                'description' => 'nullable|string',
                'admin_id' => 'nullable|exists:admins,id',
                'status' => 'sometimes|in:active,inactive,completed,on_hold',
                'priority' => 'sometimes|in:low,medium,high,urgent',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'budget' => 'nullable|numeric|min:0',
                'metadata' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation error', 422, $validator->errors());
            }

            $project->update($request->all());

            return $this->successResponse($project->fresh()->load(['admin', 'accounts', 'posts']), 'Project updated successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Delete a project
     */
    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);
            $project->delete();

            return $this->successResponse(null, 'Project deleted successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Search projects
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('query', '');
            $perPage = $request->get('per_page', 15);

            $projects = Project::where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->orWhere('slug', 'like', "%{$query}%")
                ->latest()
                ->paginate($perPage);

            return $this->paginatedResponse($projects, 'Search results retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
