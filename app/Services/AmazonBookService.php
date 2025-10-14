<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AmazonBookService
{
    /**
     * Fetch book title and cover image from Amazon by ASIN
     * For educational purposes only
     *
     * @param string $asin
     * @return array
     */
    public function fetchBookData(string $asin): array
    {
        try {
            $url = "https://www.amazon.com/dp/{$asin}";

            // Make HTTP request with proper headers to mimic a browser
            // Increased timeout to 30 seconds to handle slow Amazon responses
            $response = Http::timeout(30)
                ->connectTimeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.9',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'Connection' => 'keep-alive',
                    'Upgrade-Insecure-Requests' => '1',
                ])
                ->get($url);

            if ($response->successful()) {
                $html = $response->body();

                return [
                    'success' => true,
                    'asin' => $asin,
                    'title' => $this->extractTitle($html),
                    'image' => $this->extractImage($html),
                    'url' => $url,
                ];
            }

            return [
                'success' => false,
                'asin' => $asin,
                'error' => 'Failed to fetch data from Amazon',
                'url' => $url,
            ];

        } catch (\Exception $e) {
            Log::error('Amazon Book Fetch Error', [
                'asin' => $asin,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'asin' => $asin,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Extract book title from HTML
     *
     * @param string $html
     * @return string|null
     */
    private function extractTitle(string $html): ?string
    {
        // Try multiple patterns to find the title
        $patterns = [
            '/<span id="productTitle"[^>]*>(.*?)<\/span>/is',
            '/<h1[^>]*id="title"[^>]*>(.*?)<\/h1>/is',
            '/<meta property="og:title" content="([^"]+)"/i',
            '/<title>([^<]+)<\/title>/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $matches)) {
                $title = trim(strip_tags($matches[1]));
                // Remove " : Books" or " - Amazon.com" suffixes
                $title = preg_replace('/\s*[:\-]\s*(Books|Amazon\.com).*$/i', '', $title);
                if (!empty($title) && strlen($title) > 3) {
                    return html_entity_decode($title, ENT_QUOTES, 'UTF-8');
                }
            }
        }

        return null;
    }

    /**
     * Extract book cover image from HTML
     *
     * @param string $html
     * @return string|null
     */
    private function extractImage(string $html): ?string
    {
        // Try multiple patterns to find the image - ordered by reliability
        $patterns = [
            // OG meta tag - most reliable
            '/<meta property="og:image" content="([^"]+)"/i',
            '/<meta name="og:image" content="([^"]+)"/i',
            // Standard image tags
            '/<img[^>]+id="landingImage"[^>]+src="([^"]+)"/i',
            '/<img[^>]+id="imgBlkFront"[^>]+src="([^"]+)"/i',
            '/<img[^>]+id="ebooksImgBlkFront"[^>]+src="([^"]+)"/i',
            '/<img[^>]+data-old-hires="([^"]+)"/i',
            '/<img[^>]+data-a-dynamic-image="[^"]*https:([^"]+)"/i',
            // Dynamic image with src
            '/src="(https:\/\/[^"]*images-na\.ssl-images-amazon\.com[^"]+)"/i',
            '/src="(https:\/\/[^"]*m\.media-amazon\.com[^"]+)"/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $matches)) {
                $imageUrl = trim($matches[1]);

                // Handle protocol-relative URLs
                if (strpos($imageUrl, '//') === 0) {
                    $imageUrl = 'https:' . $imageUrl;
                }

                // Clean up the URL
                if (!empty($imageUrl)) {
                    // Remove size parameters to get larger image
                    $imageUrl = preg_replace('/\._[A-Z0-9,_]+_\./', '.', $imageUrl);

                    // Validate URL
                    if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                        return $imageUrl;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Fetch book data for multiple ASINs
     *
     * @param array $asins
     * @return array
     */
    public function fetchMultipleBooks(array $asins): array
    {
        $results = [];

        foreach ($asins as $asin) {
            $results[$asin] = $this->fetchBookData($asin);
            // Add a small delay to avoid rate limiting
            usleep(500000); // 0.5 seconds
        }

        return $results;
    }
}
