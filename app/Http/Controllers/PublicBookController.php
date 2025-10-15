<?php

namespace App\Http\Controllers;

use App\Services\AmazonBookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PublicBookController extends Controller
{
    /**
     * Fetch book data from Amazon by ASIN
     * For educational purposes only
     * Public endpoint - no authentication required
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
