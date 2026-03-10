<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vision;
use Illuminate\Http\Request;

class VisionController extends Controller
{
    /**
     * GET /api/vision
     */
    public function show()
    {
        $vision = Vision::first();

        if (!$vision) {
            return response()->json([
                'status' => false,
                'message' => 'Vision not found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Vision fetched successfully',
            'data' => $vision,
        ]);
    }
}
