<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * GET /api/organizations
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Build query
        $query = Organization::query()
            ->orderBy('created_at', 'desc');

        // Apply search if available
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
            // Jika mau search di description juga:
            // ->orWhere('description', 'like', "%{$search}%")
        }

        // Paginate
        $organizations = $query->paginate(10);

        // Map hanya field yang diperlukan di list
        $data = $organizations->map(function ($item) {
            return [
                'id' => $item->id,
                'type' => $item->type,
                'title' => $item->title,
                'created_at' => $item->created_at,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Organizations fetched successfully',
            'data' => $data,
            'meta' => [
                'current_page' => $organizations->currentPage(),
                'last_page' => $organizations->lastPage(),
                'per_page' => $organizations->perPage(),
                'total' => $organizations->total(),
                'next_page_url' => $organizations->nextPageUrl(),
            ],
        ]);
    }


    /**
     * GET /api/organizations/{id}
     */
    public function show(Organization $organization)
    {
        return response()->json([
            'status' => true,
            'message' => 'Organization fetched successfully',
            'data' => [
                'id' => $organization->id,
                'type' => $organization->type,
                'title' => $organization->title,
                'description' => $organization->description,
                'image_url' => $organization->image_path ? asset('storage/' . $organization->image_path) : null,
                'file_url' => $organization->file_path ? asset('storage/' . $organization->file_path) : null,
                'created_at' => $organization->created_at,
                'updated_at' => $organization->updated_at,
            ],
        ]);
    }
}
