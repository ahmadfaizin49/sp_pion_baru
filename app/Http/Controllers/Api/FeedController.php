<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Financial;
use App\Models\Learning;
use App\Models\Organization;
use App\Models\Social;

class FeedController extends Controller
{
    /**
     * GET /api/feed
     */
    public function index()
    {
        $financials = Financial::all();
        $learnings = Learning::all();
        $organizations = Organization::all();
        $socials = Social::all();

        // Gunakan concat() agar collection tidak melakukan merge berdasarkan ID
        $feedItems = collect()
            ->concat($financials)
            ->concat($learnings)
            ->concat($organizations)
            ->concat($socials)
            ->sortByDesc('created_at')
            ->values()
            ->take(10);

        $data = $feedItems->map(function ($item) {
            return [
                'id' => $item->id,
                'type' => $item->type,
                'title' => $item->title,
                'created_at' => $item->created_at,
                'image_url' => $item->image_path
                    ? asset('storage/' . $item->image_path)
                    : null,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Feed fetched successfully',
            'data' => $data,
        ]);
    }

}
