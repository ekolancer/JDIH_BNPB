<?php

namespace App\Http\Controllers;

use App\Http\Resources\DokumenHukumResource;
use App\Http\Requests\MetadataApiRequest;
use App\Models\DokumenHukum;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    /**
     * Endpoint API untuk sinkronisasi data ke sistem PUU.
     */
    public function index(MetadataApiRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

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
                $query->whereHas('tipeDokumen', fn ($q) => $q->where('kode', $validated['tipeDok']));
            }

            if (!empty($validated['tahun'])) {
                $query->where('tahun', $validated['tahun']);
            }

            if (!empty($validated['judul'])) {
                $query->whereFullText('judul', $validated['judul']);
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
         * EXECUTE (with pagination)
         * ============================*/
            $perPage = min((int) ($validated['per_page'] ?? 50), 100);
            $dokumen = $query->paginate($perPage);

            $data = $dokumen->getCollection()->map(
                fn($item) => (new DokumenHukumResource($item))->toArray($request)
            );

            return response()->json(
                [
                    'data' => $data,
                    'meta' => [
                        'current_page' => $dokumen->currentPage(),
                        'last_page' => $dokumen->lastPage(),
                        'per_page' => $dokumen->perPage(),
                        'total' => $dokumen->total(),
                    ],
                ],
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
