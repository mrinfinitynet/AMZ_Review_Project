<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;

/**
 * Project Management Controller
 * ALL operations use Claude API when CLAUDE_URL is set
 */
class ProjectManagementController extends Controller
{
    protected $projectRepo;

    public function __construct(ProjectRepository $projectRepo)
    {
        $this->projectRepo = $projectRepo;
    }

    /**
     * Display list of projects
     */
    public function index()
    {
        try {
            $projects = $this->projectRepo->paginate(15);

            return view('admin.projects.index', compact('projects'));
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot fetch projects: ' . $e->getMessage());
        }
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.projects.create');
    }

    /**
     * Store new project
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:projects,slug',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,completed,on_hold',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
        ]);

        try {
            $this->projectRepo->create($validated);

            return redirect()->route('admin.projects.index')
                ->with('success', 'Project created successfully via ' . (config('claude.enabled') ? 'API' : 'Database'));
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot create project: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show single project
     */
    public function show($id)
    {
        try {
            $project = $this->projectRepo->find($id);

            return view('admin.projects.show', compact('project'));
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot fetch project: ' . $e->getMessage());
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        try {
            $project = $this->projectRepo->find($id);

            return view('admin.projects.edit', compact('project'));
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot fetch project: ' . $e->getMessage());
        }
    }

    /**
     * Update project
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:projects,slug,' . $id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,completed,on_hold',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
        ]);

        try {
            $this->projectRepo->update($id, $validated);

            return redirect()->route('admin.projects.index')
                ->with('success', 'Project updated successfully via ' . (config('claude.enabled') ? 'API' : 'Database'));
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot update project: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete project
     */
    public function destroy($id)
    {
        try {
            $this->projectRepo->delete($id);

            return redirect()->route('admin.projects.index')
                ->with('success', 'Project deleted successfully via ' . (config('claude.enabled') ? 'API' : 'Database'));
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot delete project: ' . $e->getMessage());
        }
    }
}
