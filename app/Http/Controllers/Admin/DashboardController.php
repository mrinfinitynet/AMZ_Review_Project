<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AmazonReviewProject;
use App\Models\AmazonReviewAccount;
use App\Models\AmazonReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with data
     */
    public function dashboard()
    {
        try {
            $totalAdmins = User::where('role', 'admin')->count();
            $totalProjects = AmazonReviewProject::select('project_id')->distinct()->count();
            $totalAccounts = AmazonReviewAccount::count();
            $totalReviews = AmazonReviewProject::count();

            $pendingReviews = AmazonReviewProject::where('status', 'pending')->count();
            $completedReviews = AmazonReviewProject::where('status', 'approved')->count();
            $rejectedReviews = AmazonReviewProject::where('status', 'rejected')->count();

            $successRate = $totalReviews > 0
                ? round(($completedReviews / $totalReviews) * 100, 1)
                : 0;

            $stats = [
                'totalAccounts' => $totalAccounts,
                'totalProjects' => $totalProjects,
                'pendingReviews' => $pendingReviews,
                'completedReviews' => $completedReviews,
                'successRate' => $successRate
            ];

            return view("admin.dashboard", compact('stats'));
        } catch (\Exception $e) {
            // If there's an error, show dashboard with zero stats
            $stats = [
                'totalAccounts' => 0,
                'totalProjects' => 0,
                'pendingReviews' => 0,
                'completedReviews' => 0,
                'successRate' => 0
            ];
            return view("admin.dashboard", compact('stats'));
        }
    }

    /**
     * Get dashboard statistics via AJAX
     */
    public function getStatistics()
    {
        try {
            $totalAdmins = User::where('role', 'admin')->count();
            $totalProjects = AmazonReviewProject::select('project_id')->distinct()->count();
            $totalAccounts = AmazonReviewAccount::count();
            $totalReviews = AmazonReviewProject::count();

            $pendingReviews = AmazonReviewProject::where('status', 'pending')->count();
            $completedReviews = AmazonReviewProject::where('status', 'approved')->count();

            $successRate = $totalReviews > 0
                ? round(($completedReviews / $totalReviews) * 100, 1)
                : 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'totalAccounts' => $totalAccounts,
                    'totalProjects' => $totalProjects,
                    'pendingReviews' => $pendingReviews,
                    'completedReviews' => $completedReviews,
                    'successRate' => $successRate
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get project status distribution via AJAX
     */
    public function getProjectStatus()
    {
        try {
            $projects = AmazonReviewProject::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get()
                ->map(function($item) {
                    return [
                        'status' => $item->status,
                        'total' => $item->total
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $projects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load project status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get accounts by type via AJAX
     */
    public function getAccountsByType()
    {
        try {
            $accounts = AmazonReviewAccount::select('type', DB::raw('count(*) as total'))
                ->groupBy('type')
                ->get()
                ->map(function($item) {
                    return [
                        'type' => $item->type ?: 'Uncategorized',
                        'total' => $item->total
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $accounts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load accounts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent activities via AJAX
     */
    public function getRecentActivities()
    {
        try {
            $recentActivities = AmazonReviewProject::latest()
                ->take(10)
                ->get()
                ->map(function($project) {
                    return [
                        'project_id' => $project->project_id ?? 0,
                        'account_id' => $project->account_id ?? 0,
                        'rating' => $project->rating ?? 5,
                        'status' => $project->status ?? 'pending',
                        'created_at' => $project->created_at ? $project->created_at->diffForHumans() : 'N/A'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $recentActivities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load activities: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reviews per day via AJAX
     */
    public function getReviewsPerDay()
    {
        try {
            $reviewsPerDay = collect();
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $count = AmazonReviewProject::whereDate('created_at', $date->toDateString())->count();

                $reviewsPerDay->push([
                    'date' => $date->format('M d'),
                    'total' => $count
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $reviewsPerDay
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load reviews data: ' . $e->getMessage()
            ], 500);
        }
    }
}
