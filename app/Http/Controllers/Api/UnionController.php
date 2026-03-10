<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Union;
use Illuminate\Http\Request;

class UnionController extends Controller
{
    /**
     * GET /api/unions
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Build query
        $query = Union::query()
            ->orderBy('created_at', 'desc');

        // Apply search if available
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
            // Jika mau search di description juga:
            // ->orWhere('description', 'like', "%{$search}%")
        }

        // Paginate
        $unions = $query->paginate(10);

        // Map hanya field yang diperlukan di list
        $data = $unions->map(function ($item) {
            return [
                'id' => $item->id,
                'type' => $item->type,
                'title' => $item->title,
                'created_at' => $item->created_at,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Unions fetched successfully',
            'data' => $data,
            'meta' => [
                'current_page' => $unions->currentPage(),
                'last_page' => $unions->lastPage(),
                'per_page' => $unions->perPage(),
                'total' => $unions->total(),
                'next_page_url' => $unions->nextPageUrl(),
            ],
        ]);
    }

    /**
     * GET /api/unions/{id}
     */
    public function show(Union $union)
    {
        return response()->json([
            'status' => true,
            'message' => 'Union fetched successfully',
            'data' => [
                'id' => $union->id,
                'type' => $union->type,
                'title' => $union->title,
                'description' => $union->description,
                'image_url' => $union->image_path ? asset('storage/' . $union->image_path) : null,
                'file_url' => $union->file_path ? asset('storage/' . $union->file_path) : null,
                'created_at' => $union->created_at,
                'updated_at' => $union->updated_at,
            ],
        ]);
    }
}
