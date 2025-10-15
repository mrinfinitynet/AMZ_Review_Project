<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\AmazonReviewProject;
use App\Models\AmazonReviewAccount;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    // Verify access key and redirect to dashboard
    public function verifyKey(Request $request)
    {
        $request->validate([
            'access_key' => 'required|string'
        ]);

        $key = strtoupper(trim($request->access_key));

        // Find client by KEY field (not code)
        $client = Client::where('key', $key)->first();

        if (!$client) {
            return back()->with('error', 'Invalid access key. Please check and try again.');
        }

        if (!$client->isValid()) {
            return back()->with('error', 'This access key is inactive. Please contact support.');
        }

        // Store client ID in session (more reliable than storing the key string)
        $request->session()->put('user_client_id', $client->id);
        $request->session()->put('user_access_key', $key);

        // Track access
        $client->trackAccess();

        return redirect()->route('user.dashboard');
    }

    // Show user dashboard
    public function dashboard(Request $request)
    {
        // Get client ID from session
        $clientId = $request->session()->get('user_client_id');

        if (!$clientId) {
            return redirect('/')->with('error', 'Please enter your access key first.');
        }

        // Find client by ID
        $client = Client::find($clientId);

        if (!$client || !$client->isValid()) {
            $request->session()->flush(); // Clear all session data
            return redirect('/')->with('error', 'Session expired. Please enter your access key again.');
        }

        // Get projects based on client code
        $clientCode = $client->code;

        // Get ALL projects and group by project_id (same as admin panel)
        $projectsByGroup = AmazonReviewProject::where("type", $clientCode)
            ->orderBy("project_id", "DESC")
            ->get()
            ->groupBy("project_id");

        // Pending: projects with at least one 'pending' status
        $pendingProjects = $projectsByGroup->filter(function($reviews) {
            return $reviews->contains(function($review) {
                return $review->status === 'pending';
            });
        })->map(function($reviews, $projectId) {
            return [
                "project_id" => $projectId,
                "book_asin" => $reviews->first()->book_asin ?? null,
                "review_link" => $reviews->first()->review_link ?? null,
                "created_at" => $reviews->first()->created_at,
                "updated_at" => $reviews->first()->updated_at,
                "reviews" => $reviews
            ];
        })->values();

        // Completed: projects where ALL reviews are approved/rejected/deleted
        $completedProjects = $projectsByGroup->filter(function($reviews) {
            return $reviews->every(function($review) {
                return in_array($review->status, ['approved', 'rejected', 'delete']);
            });
        })->map(function($reviews, $projectId) {
            return [
                "project_id" => $projectId,
                "book_asin" => $reviews->first()->book_asin ?? null,
                "review_link" => $reviews->first()->review_link ?? null,
                "created_at" => $reviews->first()->created_at,
                "updated_at" => $reviews->first()->updated_at,
                "reviews" => $reviews
            ];
        })->values();

        // Statistics
        $stats = [
            'total_pending' => $pendingProjects->count(),
            'total_completed' => $completedProjects->count(),
            'total_projects' => $pendingProjects->count() + $completedProjects->count(),
        ];

        return view('user.dashboard', compact('client', 'pendingProjects', 'completedProjects', 'stats'));
    }

    // Search projects
    public function searchProjects(Request $request)
    {
        // Get client ID from session
        $clientId = $request->session()->get('user_client_id');

        if (!$clientId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $client = Client::find($clientId);

        if (!$client || !$client->isValid()) {
            return response()->json(['error' => 'Invalid session'], 401);
        }

        $search = $request->get('search');
        $clientCode = $client->code;

        // Search using AmazonReviewProject and group by project_id (same as admin panel)
        $projectsByGroup = AmazonReviewProject::where('type', $clientCode)
            ->where(function($query) use ($search) {
                $query->where('book_asin', 'like', "%{$search}%")
                      ->orWhere('review_link', 'like', "%{$search}%")
                      ->orWhere('project_id', 'like', "%{$search}%")
                      ->orWhere('review_title', 'like', "%{$search}%");
            })
            ->orderBy('project_id', 'desc')
            ->limit(50)
            ->get()
            ->groupBy('project_id');

        // Map to consistent format
        $results = $projectsByGroup->map(function($reviews, $projectId) {
            return [
                "project_id" => $projectId,
                "book_asin" => $reviews->first()->book_asin ?? null,
                "review_link" => $reviews->first()->review_link ?? null,
                "created_at" => $reviews->first()->created_at,
                "updated_at" => $reviews->first()->updated_at,
                "reviews" => $reviews
            ];
        })->values()->take(20);

        return response()->json($results);
    }

    // Logout (clear session)
    public function logout(Request $request)
    {
        // Properly clear all user session data
        $request->session()->flush();

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
