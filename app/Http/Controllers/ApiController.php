<?php

namespace App\Http\Controllers;

use App\Http\Resources\DokumenHukumResource;
use App\Models\DokumenHukum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            // Validate request parameters for pagination and filtering
            $validated = $request->validate([
                'per_page' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1',
                'tipeDok' => 'nullable|string|in:Peraturan,Monografi,Artikel,Putusan',
                'tahun' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'judul' => 'nullable|string|max:255',
                'subjek' => 'nullable|string|max:255',
                'bidangHukum' => 'nullable|string|max:255',
                'sort_by' => 'nullable|string|in:id,tahun,judul,created_at',
                'sort_order' => 'nullable|string|in:asc,desc',
            ]);

            $perPage = $validated['per_page'] ?? 15; // Default 15 items per page, max 100
            $query = DokumenHukum::query();

            // Apply filters if provided
            if (isset($validated['tipeDok'])) {
                $query->where('tipeDok', $validated['tipeDok']);
            }

            if (isset($validated['tahun'])) {
                $query->where('tahun', $validated['tahun']);
            }

            if (isset($validated['judul'])) {
                $query->where('judul', 'like', '%' . $validated['judul'] . '%');
            }

            if (isset($validated['subjek'])) {
                $query->where('subjek', 'like', '%' . $validated['subjek'] . '%');
            }

            if (isset($validated['bidangHukum'])) {
                $query->where('bidangHukum', $validated['bidangHukum']);
            }

            // Apply sorting
            $sortBy = $validated['sort_by'] ?? 'created_at';
            $sortOrder = $validated['sort_order'] ?? 'desc';
            $query->orderBy($sortBy, $sortOrder);

            // Paginate the results
            // $dokumenHukums = $query->paginate($perPage); uncommented to enable pagination
            
            // Now fetch all results without pagination
            $dokumenHukums = $query->get();

            // Return paginated resource collection
            return DokumenHukumResource::collection($dokumenHukums);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'message' => 'Invalid request parameters',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('API Error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'error' => 'Internal server error',
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }
}
