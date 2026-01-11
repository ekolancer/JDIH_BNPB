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
            $validated = $request->validate([
                'per_page'    => 'nullable|integer|min:1|max:100',
                'page'        => 'nullable|integer|min:1',
                'tipeDok'     => 'nullable|string|in:Peraturan,Monografi,Artikel,Putusan',
                'tahun'       => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'judul'       => 'nullable|string|max:255',
                'subjek'      => 'nullable|string|max:255',
                'bidangHukum' => 'nullable|string|max:255',
                'sort_by'     => 'nullable|string|in:id,tahun,judul,created_at',
                'sort_order'  => 'nullable|string|in:asc,desc',
            ]);

            $query = DokumenHukum::query()
                ->with([
                    'jenisDokumen:id,nama,singkatan',
                    'bidangHukumRef:id,nama',
                    'subjekRef:id,nama',
                ]);

            /* ============================
         * FILTER
         * ============================*/

            if (!empty($validated['tipeDok'])) {
                $query->where('tipeDok', $validated['tipeDok']);
            }

            if (!empty($validated['tahun'])) {
                $query->where('tahun', $validated['tahun']);
            }

            if (!empty($validated['judul'])) {
                $query->where('judul', 'like', '%' . $validated['judul'] . '%');
            }

            // 🔹 SUBJEK (via relasi)
            if (!empty($validated['subjek'])) {
                $query->whereHas('subjekRef', function ($q) use ($validated) {
                    $q->where('nama', 'like', '%' . $validated['subjek'] . '%');
                });
            }

            // 🔹 BIDANG HUKUM (via ref)
            if (!empty($validated['bidangHukum'])) {
                $query->whereHas('bidangHukumRef', function ($q) use ($validated) {
                    $q->where('nama', 'like', '%' . $validated['bidangHukum'] . '%');
                });
            }

            /* ============================
         * SORTING
         * ============================*/
            $sortBy    = $validated['sort_by'] ?? 'tahun';
            $sortOrder = $validated['sort_order'] ?? 'desc';

            $query->orderBy($sortBy, $sortOrder);

            /* ============================
         * EXECUTE
         * ============================*/
            $dokumen = $query->get();

            $data = $dokumen->map(
                fn($item) => (new DokumenHukumResource($item))->toArray($request)
            );

            return response()->json(
                $data,
                200,
                [],
                JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error'   => 'Validation failed',
                'details' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('API Error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error'   => 'Internal server error',
                'message' => 'Unexpected error',
            ], 500);
        }
    }
}
