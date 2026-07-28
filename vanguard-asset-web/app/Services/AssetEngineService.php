<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class AssetEngineService
{
    protected string $baseUrl;

    public function __construct()
    {
        // Default to localhost:8000 where FastAPI usually runs
        $this->baseUrl = config('services.asset_engine.url', env('ASSET_ENGINE_URL', 'http://127.0.0.1:8001'));
    }

    /**
     * Send asset data to Python FastAPI OOP Engine to calculate depreciation and generate audit log.
     *
     * @param Asset $asset
     * @param string $depreciationMethod Strategy Pattern method name
     * @return array
     * @throws Exception
     */
    public function calculateDepreciation(Asset $asset, string $depreciationMethod = 'straight_line'): array
    {
        $payload = [
            'asset_id' => $asset->id,
            'name' => $asset->name,
            'type' => $asset->type,
            'purchase_cost' => (float) $asset->purchase_cost,
            'purchase_date' => $asset->purchase_date->format('Y-m-d'),
            'depreciation_method' => $depreciationMethod,
        ];

        // Append type-specific fields from detail_json
        $details = $asset->detail_json;
        if (strtolower($asset->type) === 'physical') {
            $payload['serial_number'] = $details['serial_number'] ?? '';
            $payload['maintenance_interval'] = isset($details['maintenance_interval']) ? (int) $details['maintenance_interval'] : 0;
        } elseif (strtolower($asset->type) === 'digital') {
            $payload['license_key'] = $details['license_key'] ?? '';
            $payload['expiry_date'] = $details['expiry_date'] ?? '';
        }

        try {
            // Post payload to FastAPI endpoint with a timeout of 5 seconds
            $response = Http::timeout(5)->post($this->baseUrl . '/api/asset/depreciation', $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            Log::error('AssetEngineService calculation failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload' => $payload
            ]);

            return [
                'success' => false,
                'error' => 'FastAPI Engine Error: ' . ($response->json('detail') ?? 'Unknown error occurrence.')
            ];

        } catch (Exception $e) {
            Log::error('AssetEngineService connection failed', [
                'message' => $e->getMessage(),
                'url' => $this->baseUrl
            ]);

            return [
                'success' => false,
                'error' => 'Could not connect to OOP Logic Engine service. Please check if FastAPI is online.'
            ];
        }
    }
}
