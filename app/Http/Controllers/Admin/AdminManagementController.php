<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Admin Management Controller
 * ALL operations use Claude API when CLAUDE_URL is set
 */
class AdminManagementController extends Controller
{
    protected $adminRepo;

    public function __construct(AdminRepository $adminRepo)
    {
        $this->adminRepo = $adminRepo;
    }

    /**
     * Display list of admins
     */
    public function index()
    {
        try {
            $admins = $this->adminRepo->paginate(15);

            return view('admin.admins.index', compact('admins'));
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot fetch admins: ' . $e->getMessage());
        }
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store new admin
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,super_admin,moderator',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        try {
            $validated['password'] = Hash::make($validated['password']);
            unset($validated['password_confirmation']);

            $this->adminRepo->create($validated);

            return redirect()->route('admin.admins.index')
                ->with('success', 'Admin created successfully via ' . (config('claude.enabled') ? 'API' : 'Database'));
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot create admin: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show single admin
     */
    public function show($id)
    {
        try {
            $admin = $this->adminRepo->find($id);

            return view('admin.admins.show', compact('admin'));
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot fetch admin: ' . $e->getMessage());
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        try {
            $admin = $this->adminRepo->find($id);

            return view('admin.admins.edit', compact('admin'));
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot fetch admin: ' . $e->getMessage());
        }
    }

    /**
     * Update admin
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $id,
            'password' => 'nullable|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,super_admin,moderator',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        try {
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }
            unset($validated['password_confirmation']);

            $this->adminRepo->update($id, $validated);

            return redirect()->route('admin.admins.index')
                ->with('success', 'Admin updated successfully via ' . (config('claude.enabled') ? 'API' : 'Database'));
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot update admin: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete admin
     */
    public function destroy($id)
    {
        try {
            $this->adminRepo->delete($id);

            return redirect()->route('admin.admins.index')
                ->with('success', 'Admin deleted successfully via ' . (config('claude.enabled') ? 'API' : 'Database'));
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot delete admin: ' . $e->getMessage());
        }
    }

    /**
     * Search admins
     */
    public function search(Request $request)
    {
        try {
            $admins = $this->adminRepo->search(['query' => $request->get('q')]);

            return view('admin.admins.index', compact('admins'));
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot search admins: ' . $e->getMessage());
        }
    }
}
