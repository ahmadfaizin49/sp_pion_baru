<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    /**
     * GET /api/informations
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Build query
        $query = Information::query()
            ->orderBy('created_at', 'desc');

        // Apply search if available
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
            // Jika mau search di description juga:
            // ->orWhere('description', 'like', "%{$search}%")
        }

        // Paginate
        $informations = $query->paginate(10);

        // Map hanya field yang diperlukan di list
        $data = $informations->map(function ($item) {
            return [
                'id' => $item->id,
                'type' => $item->type,
                'title' => $item->title,
                'image_url' => $item->image_path ? asset('storage/' . $item->image_path) : null,
                'created_at' => $item->created_at,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Informations fetched successfully',
            'data' => $data,
            'meta' => [
                'current_page' => $informations->currentPage(),
                'last_page' => $informations->lastPage(),
                'per_page' => $informations->perPage(),
                'total' => $informations->total(),
                'next_page_url' => $informations->nextPageUrl(),
            ],
        ]);
    }


    /**
     * GET /api/informations/{id}
     */
    public function show(Information $information)
    {
        return response()->json([
            'status' => true,
            'message' => 'Information fetched successfully',
            'data' => [
                'id' => $information->id,
                'type' => $information->type,
                'title' => $information->title,
                'description' => $information->description,
                'image_url' => $information->image_path ? asset('storage/' . $information->image_path) : null,
                'file_url' => $information->file_path ? asset('storage/' . $information->file_path) : null,
                'created_at' => $information->created_at,
                'updated_at' => $information->updated_at,
            ],
        ]);
    }
}
