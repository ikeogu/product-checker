<?php


namespace  App\Http\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;

class ProductVerificationService
{

    public function verify(array $payload)
    {
        if (isset($payload['barcode'])) {
            return $this->verifyByBarcode($payload['barcode']);
        }

        if (isset($payload['text'])) {
            return $this->verifyByText($payload['text']);
        }

        if (isset($payload['image_base64']) || isset($payload['image_url'])) {
            return $this->verifyByImage([
                'url' => $payload['image_url'],
                'base64' => $payload['image_base64'],
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No valid input provided.'
        ], 422);
    }

    public function verifyByBarcode(string $barcode)
    {
        // ✅ Step 1: Search in local DB
        $product = Product::where('barcode', $barcode)->first();

        if ($product) {
            return response()->json([
                'status' => 'success',
                'source' => 'local_db',
                'data'   => $product
            ]);
        }

        // ✅ Step 2: If not found, query external API
        try {
            // Example: Using OpenFoodFacts (free public API for demo)
            $apiUrl = "https://world.openfoodfacts.org/api/v0/product/{$barcode}.json";

            $apiResponse = Http::timeout(10)->get($apiUrl);

            if ($apiResponse->successful()) {
                $apiData = $apiResponse->json();

                if (!empty($apiData['product'])) {
                    // Optional: Save minimal product record in DB for caching
                    $product = Product::create([
                        'name'       => $apiData['product']['product_name'] ?? 'Unknown',
                        'barcode'    => $barcode,
                        'category'   => $apiData['product']['categories_tags'][0] ?? 'unknown',
                        'manufacturer' => $apiData['product']['brands'] ?? 'unknown',
                        'country_of_origin' => $apiData['product']['countries'] ?? 'unknown',
                        'image_url'  => $apiData['product']['image_url'] ?? null,
                        'status'     => 'pending',
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'source' => 'external_api',
                        'data'   => $product
                    ]);
                }
            }

            return response()->json([
                'status'  => 'error',
                'message' => 'Product not found in database or external sources',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Barcode verification failed: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function verifyByText(string $text)
    {
        $products = Product::query()
            ->where('name', 'LIKE', "%{$text}%")
            ->orWhere('manufacturer', 'LIKE', "%{$text}%")
            ->orWhere('active_ingredient', 'LIKE', "%{$text}%")
            ->orWhere('category', 'LIKE', "%{$text}%")
            ->orWhere('nafdac_number', 'LIKE', "%{$text}%")
            ->orWhere('batch_number', 'LIKE', "%{$text}%")
            ->orWhere('dosage_form', 'LIKE', "%{$text}%")
            ->orWhere('strength', 'LIKE', "%{$text}%")
            ->take(15)
            ->get();

        if ($products->isNotEmpty()) {
            return response()->json([
                'status' => 'success',
                'source' => 'local_db',
                'count'  => $products->count(),
                'data'   => $products
            ]);
        }

        try {
            $apiUrl = "https://world.openfoodfacts.org/cgi/search.pl";
            $apiResponse = Http::timeout(10)->get($apiUrl, [
                'search_terms' => $text,
                'search_simple' => 1,
                'action' => 'process',
                'json' => 1,
            ]);

            if ($apiResponse->successful()) {
                $apiData = $apiResponse->json();

                if (!empty($apiData['products'])) {
                    $externalProducts = collect($apiData['products'])->map(function ($p) {
                        return [
                            'name'       => $p['product_name'] ?? 'Unknown',
                            'barcode'    => $p['code'] ?? null,
                            'category'   => $p['categories_tags'][0] ?? null,
                            'manufacturer' => $p['brands'] ?? null,
                            'country_of_origin' => $p['countries'] ?? null,
                            'image_url'  => $p['image_url'] ?? null,
                            'nafdac_number' => null, // external API won’t have this
                            'status'     => 'pending',
                        ];
                    });

                    return response()->json([
                        'status' => 'success',
                        'source' => 'external_api',
                        'data'   => $externalProducts
                    ]);
                }
            }

            return response()->json([
                'status'  => 'error',
                'message' => 'No product found with that text in DB or external sources',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Text verification failed: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function verifyByImage(array $imageData)
    {
        try {
            // Step 1: Optional — Search DB by existing image_url similarity (basic check)
            $similarProduct = Product::where('image_url', $imageData['url'])->first();

            if ($similarProduct) {
                return response()->json([
                    'status' => 'success',
                    'source' => 'local_db',
                    'data'   => $similarProduct
                ]);
            }

            // Step 2: Send image to external AI recognition service (placeholder)
            $apiUrl = "http://127.0.0.1:8001/api/verify-image"; // your Python/AI microservice
            $apiResponse = Http::timeout(20)->post($apiUrl, [
                'image_base64' => $imageData['base64'],
            ]);

            if ($apiResponse->successful()) {
                $result = $apiResponse->json();

                return response()->json([
                    'status' => 'success',
                    'source' => 'ai_service',
                    'data'   => $result
                ]);
            }

            return response()->json([
                'status'  => 'error',
                'message' => 'Image verification failed at external service',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Image verification exception: ' . $e->getMessage(),
            ], 500);
        }
    }
}
