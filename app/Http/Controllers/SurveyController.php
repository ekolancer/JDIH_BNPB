<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurveyRequest;
use App\Models\Survey;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class SurveyController extends Controller
{
    public function store(StoreSurveyRequest $request): JsonResponse
    {
        Survey::create($request->validated());
        Cache::forget('survey.summary');

        return response()->json([
            'status' => 'success',
            'message' => 'Terima kasih atas penilaian Anda!',
            'redirect_url' => route('survey.store'),
        ]);
    }

    public function getRatingDistribution(): JsonResponse
    {
        $data = $this->getCachedRatingSummary();

        return response()->json($data['distribution']);
    }

    public function getAverageRating(): JsonResponse
    {
        $data = $this->getCachedRatingSummary();

        return response()->json(['average' => $data['average']]);
    }

    public function getRatingSummary(): JsonResponse
    {
        return response()->json($this->getCachedRatingSummary());
    }

    private function getCachedRatingSummary(): array
    {
        return Cache::remember('survey.summary', 600, function () {
            $ratingCounts = Survey::selectRaw('rating, COUNT(*) as count')
                ->groupBy('rating')
                ->pluck('count', 'rating');

            $distribution = [];
            for ($i = 5; $i >= 1; $i--) {
                $distribution[] = $ratingCounts[$i] ?? 0;
            }

            $averageRating = Survey::average('rating');
            $totalRatings = Survey::count();

            return [
                'average' => round($averageRating ?? 0, 1),
                'total' => $totalRatings,
                'distribution' => $distribution,
            ];
        });
    }
}
