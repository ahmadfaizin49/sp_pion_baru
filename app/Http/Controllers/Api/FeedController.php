<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Financial;
use App\Models\Information;
use App\Models\Learning;
use App\Models\Organization;
use App\Models\Social;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    /**
     * GET /api/feed
     */
    // public function index()
    // {
    //     // Ambil semua data dari tabel feed (exclude 'information')
    //     $financials = Financial::all()->map(fn($item) => [
    //         'id' => $item->id,
    //         'type' => $item->type,
    //         'title' => $item->title,
    //         'created_at' => $item->created_at,
    //         'image_path' => $item->image_path,
    //     ]);

    //     $learnings = Learning::all()->map(fn($item) => [
    //         'id' => $item->id,
    //         'type' => $item->type,
    //         'title' => $item->title,
    //         'created_at' => $item->created_at,
    //         'image_path' => $item->image_path,
    //     ]);

    //     $organizations = Organization::all()->map(fn($item) => [
    //         'id' => $item->id,
    //         'type' => $item->type,
    //         'title' => $item->title,
    //         'created_at' => $item->created_at,
    //         'image_path' => $item->image_path,
    //     ]);

    //     $socials = Social::all()->map(fn($item) => [
    //         'id' => $item->id,
    //         'type' => $item->type,
    //         'title' => $item->title,
    //         'created_at' => $item->created_at,
    //         'image_path' => $item->image_path,
    //     ]);

    //     // Gabungkan semua collection
    //     $feedItems = $financials
    //         ->merge($learnings)
    //         ->merge($organizations)
    //         ->merge($socials)
    //         ->sortByDesc('created_at')
    //         ->take(10)
    //         ->values(); // reset key

    //     // Map field untuk response
    //     $data = $feedItems->map(fn($item) => [
    //         'id' => $item['id'],
    //         'type' => $item['type'],
    //         'title' => $item['title'],
    //         'created_at' => $item['created_at'],
    //         'image_url' => $item['image_path'] ? asset('storage/' . $item['image_path']) : null,
    //     ]);


    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Feed fetched successfully',
    //         'data' => $data,
    //     ]);
    // }

    public function index()
    {
        $financials = Financial::all();
        $learnings = Learning::all();
        $organizations = Organization::all();
        $socials = Social::all();

        $feedItems = $financials
            ->merge($learnings)
            ->merge($organizations)
            ->merge($socials)
            ->sortByDesc('created_at')
            ->take(10)
            ->values();

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
