<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders'   => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'total_users'    => User::where('is_admin', false)->count(),
            'total_revenue'  => Order::where('status', '!=', 'cancelled')->sum('total'),
        ];

        $recentOrders = Order::with('user')->latest()->take(10)->get();

        // ===== Weather API Integration =====
        $weather = $this->getWeatherData();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'weather'));
    }

    /**
     * Fetch weather data from OpenWeatherMap using Laravel HTTP Client
     */
    private function getWeatherData(): array
    {
        $apiKey = config('services.weather.key');
        $city   = config('services.weather.city', 'Manila');
        $units  = config('services.weather.units', 'metric');

        // Default fallback (dashboard still works even if API fails)
        $fallback = [
            'success'     => false,
            'message'     => 'Weather data is currently unavailable.',
            'city'        => $city,
            'temperature' => null,
            'description' => null,
            'humidity'    => null,
            'wind_speed'  => null,
            'icon'        => null,
        ];

        // If no API key is set, return fallback immediately
        if (empty($apiKey) || $apiKey === 'your_openweathermap_api_key_here') {
            $fallback['message'] = 'Weather API key is not configured.';
            return $fallback;
        }

        try {
            $response = Http::timeout(5) // 5-second timeout
                ->retry(2, 100)          // retry twice with 100ms delay
                ->get('https://api.openweathermap.org/data/2.5/weather', [
                    'q'     => $city,
                    'appid' => $apiKey,
                    'units' => $units,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success'     => true,
                    'city'        => $data['name'] ?? $city,
                    'temperature' => round($data['main']['temp'] ?? 0),
                    'description' => ucfirst($data['weather'][0]['description'] ?? 'N/A'),
                    'humidity'    => $data['main']['humidity'] ?? null,
                    'wind_speed'  => $data['wind']['speed'] ?? null,
                    'icon'        => $data['weather'][0]['icon'] ?? null,
                ];
            }

            // API returned an error (4xx / 5xx)
            Log::warning('Weather API error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            $fallback['message'] = 'Unable to retrieve weather data (API error).';
            return $fallback;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Network / timeout problems
            Log::error('Weather API connection failed: ' . $e->getMessage());
            $fallback['message'] = 'Weather service is temporarily unreachable.';
            return $fallback;

        } catch (\Exception $e) {
            // Any other unexpected error
            Log::error('Weather API unexpected error: ' . $e->getMessage());
            $fallback['message'] = 'An unexpected error occurred while fetching weather.';
            return $fallback;
        }
    }
}