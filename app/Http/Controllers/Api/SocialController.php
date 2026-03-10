<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Social;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    /**
     * GET /api/socials
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Build query
        $query = Social::query()
            ->orderBy('created_at', 'desc');

        // Apply search if available
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
            // Jika mau search di description juga:
            // ->orWhere('description', 'like', "%{$search}%")
        }

        // Paginate
        $socials = $query->paginate(10);

        // Map hanya field yang diperlukan di list
        $data = $socials->map(function ($item) {
            return [
                'id' => $item->id,
                'type' => $item->type,
                'title' => $item->title,
                'created_at' => $item->created_at,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Socials fetched successfully',
            'data' => $data,
            'meta' => [
                'current_page' => $socials->currentPage(),
                'last_page' => $socials->lastPage(),
                'per_page' => $socials->perPage(),
                'total' => $socials->total(),
                'next_page_url' => $socials->nextPageUrl(),
            ],
        ]);
    }


    /**
     * GET /api/socials/{id}
     */
    public function show(Social $social)
    {
        return response()->json([
            'status' => true,
            'message' => 'Social fetched successfully',
            'data' => [
                'id' => $social->id,
                'type' => $social->type,
                'title' => $social->title,
                'description' => $social->description,
                'image_url' => $social->image_path ? asset('storage/' . $social->image_path) : null,
                'file_url' => $social->file_path ? asset('storage/' . $social->file_path) : null,
                'created_at' => $social->created_at,
                'updated_at' => $social->updated_at,
            ],
        ]);
    }
}
