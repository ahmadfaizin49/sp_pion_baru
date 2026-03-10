<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Financial;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    /**
     * GET /api/financials
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Build query
        $query = Financial::query()
            ->orderBy('created_at', 'desc');

        // Apply search if available
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
            // Jika mau search di description juga:
            // ->orWhere('description', 'like', "%{$search}%")
        }

        // Paginate
        $financials = $query->paginate(10);

        // Map hanya field yang diperlukan di list
        $data = $financials->map(function ($item) {
            return [
                'id' => $item->id,
                'type' => $item->type,
                'title' => $item->title,
                'created_at' => $item->created_at,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Financials fetched successfully',
            'data' => $data,
            'meta' => [
                'current_page' => $financials->currentPage(),
                'last_page' => $financials->lastPage(),
                'per_page' => $financials->perPage(),
                'total' => $financials->total(),
                'next_page_url' => $financials->nextPageUrl(),
            ],
        ]);
    }


    /**
     * GET /api/financials/{id}
     */
    public function show(Financial $financial)
    {
        return response()->json([
            'status' => true,
            'message' => 'Financial fetched successfully',
            'data' => [
                'id' => $financial->id,
                'type' => $financial->type,
                'title' => $financial->title,
                'description' => $financial->description,
                'image_url' => $financial->image_path ? asset('storage/' . $financial->image_path) : null,
                'file_url' => $financial->file_path ? asset('storage/' . $financial->file_path) : null,
                'created_at' => $financial->created_at,
                'updated_at' => $financial->updated_at,
            ],
        ]);
    }
}
