<?php

namespace App\Jobs;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ResolveVisitorGeo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $backoff = 10;

    public function __construct(
        protected int $visitorId,
        protected string $ip
    ) {}

    public function handle(): void
    {
        $visitor = Visitor::find($this->visitorId);

        if (!$visitor) {
            return;
        }

        $country = 'Unknown';
        $city = '-';

        try {
            $apiResponse = Http::timeout(5)->get("https://ipapi.co/{$this->ip}/json/");
            $data = $apiResponse->json();

            // Jika rate-limited atau gagal, fallback ke ipwho.is
            if (isset($data['error']) || empty($data['country'])) {
                Log::channel('visitor')->info("ipapi.co failed for {$this->ip}, using ipwho.is fallback.");
                $apiResponse = Http::timeout(5)->get("https://ipwho.is/{$this->ip}");
                $data = $apiResponse->json();
            }

            $country = $data['country'] ?? 'Unknown';
            $city = $data['city'] ?? '-';
        } catch (\Throwable $e) {
            Log::channel('visitor')->warning("Geo lookup failed for {$this->ip}: " . $e->getMessage());
            return;
        }

        // Jika bukan Indonesia, hapus record (sesuai BR-05)
        if (strtolower($country) !== 'indonesia') {
            Log::channel('visitor')->info("Removing non-Indonesian visitor {$this->ip} ({$country})");
            $visitor->delete();
            return;
        }

        // Update geo data
        $visitor->update([
            'country' => $country,
            'city' => $city,
        ]);

        Log::channel('visitor')->info("Geo resolved for {$this->ip}: {$country}, {$city}");
    }
}
