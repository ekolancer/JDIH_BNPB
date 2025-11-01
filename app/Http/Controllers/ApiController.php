<?php

namespace App\Http\Controllers;

use App\Http\Resources\DokumenHukumResource;
use App\Models\DokumenHukum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    /**
     * Endpoint API untuk sinkronisasi data ke sistem PUU.
     * Mengembalikan array JSON langsung (tanpa wrapper "data").
     */
    public function index(Request $request)
    {
        try {
            // Validasi parameter request
            $validated = $request->validate([
                'per_page'     => 'nullable|integer|min:1|max:100',
                'page'         => 'nullable|integer|min:1',
                'tipeDok'      => 'nullable|string|in:Peraturan,Monografi,Artikel,Putusan',
                'tahun'        => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'judul'        => 'nullable|string|max:255',
                'subjek'       => 'nullable|string|max:255',
                'bidangHukum'  => 'nullable|string|max:255',
                'sort_by'      => 'nullable|string|in:id,tahun,judul,created_at',
                'sort_order'   => 'nullable|string|in:asc,desc',
            ]);

            // Parameter dasar
            $perPage = $validated['per_page'] ?? 15;
            $query   = DokumenHukum::query();

            // Filter opsional
            if (!empty($validated['tipeDok'])) {
                $query->where('tipeDok', $validated['tipeDok']);
            }

            if (!empty($validated['tahun'])) {
                $query->where('tahun', $validated['tahun']);
            }

            if (!empty($validated['judul'])) {
                $query->where('judul', 'like', '%' . $validated['judul'] . '%');
            }

            if (!empty($validated['subjek'])) {
                $query->where('subjek', 'like', '%' . $validated['subjek'] . '%');
            }

            if (!empty($validated['bidangHukum'])) {
                $query->where('bidangHukum', $validated['bidangHukum']);
            }

            // Urutan hasil
            $sortBy    = $validated['sort_by'] ?? 'tahun';
            $sortOrder = $validated['sort_order'] ?? 'desc';
            $query->orderBy($sortBy, $sortOrder);

            // Ambil data (tanpa pagination agar semua data keluar)
            $dokumenHukums = $query->get();

            // Ubah resource collection menjadi array biasa
            $data = $dokumenHukums->map(function ($item) {
                return (new DokumenHukumResource($item))->toArray(request());
            });

            // Return sebagai array JSON langsung (tanpa key "data")
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Error validasi parameter input
            return response()->json([
                'error'   => 'Validation failed',
                'message' => 'Invalid request parameters',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Error umum
            Log::error('API Error: ' . $e->getMessage(), [
                'request'     => $request->all(),
                'user_agent'  => $request->userAgent(),
                'ip'          => $request->ip(),
            ]);

            return response()->json([
                'error'   => 'Internal server error',
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }
}
