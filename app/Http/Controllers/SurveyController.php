<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurveyRequest;
use App\Models\Survey;

class SurveyController extends Controller
{
    // Function Simpan Rating
    public function store(StoreSurveyRequest $request)
    {
        $validated = $request->validated();

        // Simpan data ke database
        Survey::create($validated);

        // Kembalikan respon JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Terima kasih atas penilaian Anda!',
            'redirect_url' => route('survey.store'), // Atau URL lain untuk redirect
        ]);
    }

    public function getRatingDistribution()
    {
        $ratingCounts = Survey::selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating');

        // Pastikan semua nilai rating (1-5) ada, isi 0 jika tidak ditemukan
        $data = [];
        for ($i = 5; $i >= 1; $i--) {
            $data[] = isset($ratingCounts[$i]) ? $ratingCounts[$i] : 0;
        }

        return response()->json($data);
    }

    public function getAverageRating()
    {
        $averageRating = Survey::average('rating'); // Menghitung rata-rata kolom 'rating'
        return response()->json(['average' => round($averageRating, 1)]); // Membulatkan ke 1 desimal
    }

    public function getRatingSummary()
    {
        $averageRating = Survey::average('rating'); // Menghitung rata-rata rating
        $totalRatings = Survey::count(); // Menghitung jumlah total penilaian

        return response()->json([
            'average' => round($averageRating, 1), // Membulatkan rata-rata ke 1 desimal
            'total' => $totalRatings
        ]);
    }
}
