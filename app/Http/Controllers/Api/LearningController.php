<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Learning;
use Illuminate\Http\Request;

class LearningController extends Controller
{
    /**
     * GET /api/learnings
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Build query
        $query = Learning::query()
            ->orderBy('created_at', 'desc');

        // Apply search if available
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
            // Jika mau search di description juga:
            // ->orWhere('description', 'like', "%{$search}%")
        }

        // Paginate
        $learnings = $query->paginate(10);

        // Map hanya field yang diperlukan di list
        $data = $learnings->map(function ($item) {
            return [
                'id' => $item->id,
                'type' => $item->type,
                'title' => $item->title,
                'created_at' => $item->created_at,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Learnings fetched successfully',
            'data' => $data,
            'meta' => [
                'current_page' => $learnings->currentPage(),
                'last_page' => $learnings->lastPage(),
                'per_page' => $learnings->perPage(),
                'total' => $learnings->total(),
                'next_page_url' => $learnings->nextPageUrl(),
            ],
        ]);
    }


    /**
     * GET /api/learnings/{id}
     */
    public function show(Learning $learning)
    {
        return response()->json([
            'status' => true,
            'message' => 'Learning fetched successfully',
            'data' => [
                'id' => $learning->id,
                'type' => $learning->type,
                'title' => $learning->title,
                'description' => $learning->description,
                'image_url' => $learning->image_path ? asset('storage/' . $learning->image_path) : null,
                'file_url' => $learning->file_path ? asset('storage/' . $learning->file_path) : null,
                'created_at' => $learning->created_at,
                'updated_at' => $learning->updated_at,
            ],
        ]);
    }
}
