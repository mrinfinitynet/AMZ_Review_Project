<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AmazonReviewAccount;
use App\Models\AmazonReviewProject;
use App\Models\AmazonReviewProjectHistory;
use App\Models\Client;
use App\Services\AmazonBookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AmazonReviewController extends Controller
{
    public $phpPath;
    public $artisanPath;
    public function __construct()
    {
        $this->phpPath = env('PHP_EXECUTABLE');
        $this->artisanPath = env('PROJECT_ARTISAN_PATH');
    }

    /*
    =====================
    accounts
    =====================
    */
    public function accounts(Request $request)
    {
        $type = $request->type;
        $user = Auth::user();
        $lastProject = AmazonReviewProject::orderby("id", "DESC")->first();
        $addToCart = Cache::get("all_accounts_add_to_cart_count_{$type}");

        if($request->isMethod("POST")){
            $accounts = AmazonReviewAccount::orderby("account_id","ASC")->where("type", $type)->get();

            return response()->json($accounts);
        }

        $lastProject =  AmazonReviewAccount::orderby("account_id","DESC")->where("type", $type)->first();

        return view("admin.pages.reviews.accounts", compact("user", "type", "lastProject", 'addToCart', 'lastProject'));
    }
    public function accountsAdd(Request $request)
    {
        // If 'type' is a route param like /accounts/{type}, it will come from route()
        $routeType = $request->route('type');

        if ($request->isMethod('POST')) {
            $type = $request->input('type');
            $account = new AmazonReviewAccount();
            $account->account_name = $request['account_name'];
            $account->account_id   = $request['account_id'];
            $account->account_email = $request['account_email'];
            $account->account_password = $request['account_password'];
            $account->type         = (string) $type;
            $account->total_review = 0;
            $account->save();

            return redirect()
                ->route('admin.review.accounts', ['type' => $type])
                ->with('success', 'You successfully added a new account!');
        }
        $type = $routeType ?? $request->input('type'); // safe for view

        // reset 
        $numberCount = count(AmazonReviewAccount::where("type", $type)->get());
        Cache::forever("all_accounts_add_to_cart_count_{$type}", $numberCount);
        AmazonReviewAccount::where("type", $type)->update([
            "last_checking" => null
        ]);

        // For GET render
        return view('admin.pages.reviews.accounts-add', compact('type'));
    }
    public function accountsEdit(Request $request, AmazonReviewAccount $account)
    {
        $type = $request->type;
        if ($request->isMethod('POST')) {

            $account->account_name = $request['account_name'];
            $account->account_id   = $request['account_id'];
            $account->account_email = $request['account_email'];
            $account->account_password = $request['account_password'];
            $account->save();

            return redirect()
                ->route('admin.review.accounts', ['type' => (string) $type])
                ->with('success', 'You successfully edited the account!');
        }

        // For GET render
        $type = $routeType ?? $request->input('type'); // safe for view
        return view('admin.pages.reviews.accounts-edit', compact('type', 'account'));
    }
    public function accountsAddCart(Request $request)
    {
        $count = Cache::get("all_accounts_add_to_cart_count_{$request->type}");
        $lastProject =  AmazonReviewAccount::orderby("account_id","DESC")->where("type", $request->type)->first();
        $nextCount = $count - 1;
        $nextId = ($lastProject->account_id - $count) + 1;
        if($nextCount < 1){
            return [
                "status" => false,
                "msg" => "All accounts checked",
                "count" => $count
            ];
        }

        $command = "$this->phpPath \"$this->artisanPath\" app:add-to-cart {$nextId}";
        $output = shell_exec($command);
        $pattern = '/\{\s+status:\s*(.*?),\s+msg:\s*(.*?)}/s';
        preg_match($pattern, $output, $matches);
        if (isset($matches[1])) {
            $status = $matches[1] != 'false' ? "approved" : "rejected";
            $msg = $matches[2];

            $account = AmazonReviewAccount::where("account_id", $nextId)->where("type", $request->type)->first();
            $account->last_checking = $msg;
            $account->save();

            // Retrieve with fallback
            Cache::forever("all_accounts_add_to_cart_count_{$request->type}", $nextCount);

            return [
                "status" => true,
                "count" => $nextId
            ];
        }else{
            return [
                "status" => false,
                "msg" => "Element not found:Backend-".$output,
                "count" => $count
            ];
        }
    }


    /*
    =====================
    Projects
    =====================
    */
    // Pending Projects - Show projects with at least one "pending" status
    public function projectsPending(Request $request)
    {
        $type = $request->type;
        $user = Auth::user();

        $projectsByGroup = AmazonReviewProject::where("type", $type)
            ->orderBy("project_id", "DESC")
            ->get()
            ->groupBy("project_id");

        // Filter only projects that have at least one pending review
        $results = $projectsByGroup->filter(function($reviews) {
            return $reviews->contains(function($review) {
                return $review->status === 'pending';
            });
        })->map(function($reviews, $projectId) {
            return [
                "project_id" => $projectId,
                "book_asin" => $reviews->first()->book_asin ?? null,
                "reviews" => $reviews->map(function($review) {
                    return [
                        "id" => $review->id,
                        "account_id" => $review->account_id,
                        "review_title" => $review->review_title,
                        "review_description" => $review->review_description,
                        "rating" => $review->rating,
                        "status" => $review->status,
                        "book_asin" => $review->book_asin,
                    ];
                })->values()
            ];
        })->values();

        return view("admin.pages.reviews.projects-pending", compact("user", "type", "results"));
    }

    // Archive Projects - Show projects where all reviews are "approved" or "delete"
    public function projectsArchive(Request $request)
    {
        $type = $request->type;
        $user = Auth::user();

        $projectsByGroup = AmazonReviewProject::where("type", $type)
            ->orderBy("project_id", "DESC")
            ->get()
            ->groupBy("project_id");

        // Filter only projects where ALL reviews are either approved, rejected, or delete (no pending)
        $results = $projectsByGroup->filter(function($reviews) {
            return $reviews->every(function($review) {
                return in_array($review->status, ['approved', 'rejected', 'delete']);
            });
        })->map(function($reviews, $projectId) {
            return [
                "project_id" => $projectId,
                "book_asin" => $reviews->first()->book_asin ?? null,
                "reviews" => $reviews->map(function($review) {
                    return [
                        "id" => $review->id,
                        "account_id" => $review->account_id,
                        "review_title" => $review->review_title,
                        "review_description" => $review->review_description,
                        "rating" => $review->rating,
                        "status" => $review->status,
                        "book_asin" => $review->book_asin,
                    ];
                })->values()
            ];
        })->values();

        return view("admin.pages.reviews.projects-archive", compact("user", "type", "results"));
    }

    // Keep old method for backward compatibility (redirect to pending)
    public function projects(Request $request)
    {
        return redirect()->route('admin.review.projectsPending', ['type' => $request->type]);
    }
    public function projectsAdd(Request $request)
    {
        $routeType = $request->route('type');

        if ($request->isMethod('POST')) {
            // $reviewIds = $request->input('review_ids');
            // $reviewTitles = $request->input('review_titles');
            // $reviewDescriptions = $request->input('review_descriptions');
            // $ratings = $request->input('ratings');
            $json = json_decode($request->input('review_json'));

            // store  
            // foreach ($reviewIds as $index => $id) {
            //     $routeType = $request->input('type');
            //     $project = new AmazonReviewProject();
            //     $project->type = $routeType;
            //     $project->project_id = $request->input('project_id');
            //     $project->review_link = $request->input('review_link');
            //     $project->account_id = $reviewIds[$index] ?? '';
            //     $project->review_title = $reviewTitles[$index] ?? '';
            //     $project->review_description = $reviewDescriptions[$index] ?? '';
            //     $project->rating = $ratings[$index] ?? 5;
            //     $project->status = 'pending';
            //     $project->save();
            // }

            foreach ($json as $id) {
                $routeType = $request->input('type');
                $project = new AmazonReviewProject();
                $project->type = $routeType;
                $project->project_id = $request->input('project_id');
                $project->book_asin = $request->input('book_asin');
                $project->account_id = $id->review_id ?? '';
                $project->review_title = $id->review_title ?? '';
                $project->review_description = $id->review_description ?? '';
                $project->rating = $id->rating ?? 5;
                $project->status = 'pending';
                $project->save();
            }


            return redirect()
                ->route('admin.review.projectsPending', ['type' => $routeType])
                ->with('success', 'You successfully added the project!');

        }

        // For GET render
        $type = $routeType ?? $request->input('type');
        $lastProject = AmazonReviewProject::orderby("id", "DESC")->first();

        return view('admin.pages.reviews.projects-add', compact('type', 'lastProject'));
    }
    public function stopReview($project_id)
    {
        AmazonReviewProject::where("project_id", $project_id)->update([
            "status" => "delete"
        ]);

        return redirect()->back();
    }
    public function findProject(Request $request)
    {
        $asin = $request->asin;
        $client = $request->client;

        $pr = AmazonReviewProject::where("type", $client)
            ->where("book_asin", $asin)
            ->first();

        return $pr;
    }

    /*
    =====================
    submit 
    =====================
    */
    public function submit(Request $request)
    {
        $type = $request->type ?? 'Client1';
        $clients = Client::getActive();

        $projectsByGroup = AmazonReviewProject::where('type', $type)
            ->where('status', 'pending')
            ->orderBy('id', 'asc')
            ->get()
            ->groupBy('project_id')
            ->map(function ($group) {
                return $group->first();
            });
        $reviewIds = $projectsByGroup
        ->values()
        ->pluck('id')
        ->unique()
        ->sort()
        ->values()
        ->toArray();
        Cache::remember("selected_project_ids_{$type}", now()->addHours(24), function () use ($reviewIds, $type) {
            // AmazonReviewProjectHistory::where('type', $type)->delete();
            return $reviewIds;
        });

        // Retrieve with fallback
        $ids = Cache::get("selected_project_ids_{$type}", []);
        $histories = AmazonReviewProjectHistory::where('type', $type)->orderBy("id", "DESC")->get();

        return view("admin.pages.reviews.submit", compact('ids', 'type', 'histories', 'clients'));
    }
    public function startReview(Request $request)
    {
        $type = $request->type ?? 'Client1';
        $ids = Cache::get("selected_project_ids_{$type}", []);
        if ($request->has('review_id')) {
            $review_id = $request->review_id;
        } else {
            if(!isset($ids[0])){
                return [
                    "status" => false,
                    "msg" => "All review complete!",
                    "histories" => [],
                    "ids" => $ids
                ];
            }
            $review_id =  $ids[0];
        }

        $project = AmazonReviewProject::find($review_id);

        $command = "$this->phpPath \"$this->artisanPath\" app:submit-review {$review_id}";
        $output = shell_exec($command);
        $pattern = '/\{\s+status:\s*(.*?),\s+msg:\s*(.*?)}/s';
        preg_match($pattern, $output, $matches);
        if (isset($matches[1])) {
            $status = $matches[1] != 'false' ? "approved" : "rejected";
            $msg = $matches[2];

            if($request->review_id){
                $history = AmazonReviewProjectHistory::where("review_id", $review_id)->first();
                $history->msg = $msg;
                $history->status = $status;
                $history->save();

                return redirect()->back();
            }else{
                $history = new AmazonReviewProjectHistory();
                $history->review_id = $review_id;
                $history->project_id = $project->project_id;
                $history->account_id = $project->account_id;
                $history->rating = $project->rating;
                $history->msg = $msg;
                $history->status = $status;
                $history->type = $type;
                $history->save();

                $project->status = $status;
                $project->save();

                // Retrieve with fallback
                $ids = Cache::get("selected_project_ids_{$type}", []);
                $ids = array_values(array_diff($ids, [$review_id]));
                Cache::put("selected_project_ids_{$type}", $ids, now()->addHours(24));

                // Get history
                $histories = AmazonReviewProjectHistory::where('type', $type)->orderBy("id", "DESC")->get();

                return [
                    "status" => true,
                    "histories" => $histories,
                    "ids" => $ids
                ];
            }

        }else{
            return [
                "status" => false,
                "msg" => "Element not found:Backend-".$output,
                "histories" => [],
                "ids" => $ids
            ];
        }
    }
    public function clearHistory(Request $request)
    {
        $type = $request->type ?? 'Client1';
        AmazonReviewProjectHistory::where('type', $type)->delete();
        Cache::forget("selected_project_ids_{$type}");

        return back()->with('success', 'You have successfully cleared the history and cache!');
    }
    public function checkReview($review_id)
    {
        $command = "$this->phpPath \"$this->artisanPath\" app:submit-review {$review_id}";
        $output = shell_exec($command);
        $pattern = '/\{\s+status:\s*(.*?),\s+msg:\s*(.*?)}/s';
        preg_match($pattern, $output, $matches);

        return redirect()->back();
    } 
    public function checkAccount($account_id)
    {
        $command = "$this->phpPath \"$this->artisanPath\" app:check-account {$account_id}";
        $output = shell_exec($command);
        $pattern = '/\{\s+status:\s*(.*?),\s+msg:\s*(.*?)}/s';
        preg_match($pattern, $output, $matches);

        return redirect()->back();
    }
    public function updateProject($id, Request $req)
    {
        $project = AmazonReviewProject::find($id);
        $project->account_id = $req->account_id;
        $project->save();

        $history = AmazonReviewProjectHistory::where("review_id", $id)->first();
        $history->account_id = $req->account_id;
        $history->save();

        return redirect()->back();
    }



    /**
     * Fetch book data from Amazon by ASIN
     * For educational purposes only
     *
     * @param Request $request
     * @param AmazonBookService $bookService
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchBookData(Request $request, AmazonBookService $bookService)
    {
        $asin = $request->input('asin');
        $forceRefresh = $request->input('force_refresh', false);

        if (!$asin) {
            return response()->json([
                'success' => false,
                'error' => 'ASIN is required'
            ], 400);
        }

        $cacheKey = "amazon_book_data_{$asin}";

        // If force refresh, clear the cache first
        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        // Check cache first (lifetime cache - forever)
        $bookData = Cache::rememberForever($cacheKey, function() use ($asin, $bookService) {
            return $bookService->fetchBookData($asin);
        });

        // If image or title is missing and this is not a retry, suggest retry
        if ($bookData['success'] && !$forceRefresh) {
            if (empty($bookData['image']) || empty($bookData['title'])) {
                $bookData['should_retry'] = true;
            }
        }

        return response()->json($bookData);
    }
}
