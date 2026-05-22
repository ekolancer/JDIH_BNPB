<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurveyRequest;
use App\Models\Survey;
use Illuminate\Http\JsonResponse;

class SurveyController extends Controller
{
    public function store(StoreSurveyRequest $request): JsonResponse
    {
        Survey::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Terima kasih atas penilaian Anda!',
            'redirect_url' => route('survey.store'),
        ]);
    }

    public function getRatingDistribution(): JsonResponse
    {
        $ratingCounts = Survey::selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating');

        $data = [];
        for ($i = 5; $i >= 1; $i--) {
            $data[] = $ratingCounts[$i] ?? 0;
        }

        return response()->json($data);
    }

    public function getAverageRating(): JsonResponse
    {
        $averageRating = Survey::average('rating');

        return response()->json(['average' => round($averageRating ?? 0, 1)]);
    }

    public function getRatingSummary(): JsonResponse
    {
        $averageRating = Survey::average('rating');
        $totalRatings = Survey::count();

        return response()->json([
            'average' => round($averageRating ?? 0, 1),
            'total' => $totalRatings,
        ]);
    }
}
