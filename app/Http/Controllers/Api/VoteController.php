<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoteResult;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VoteController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->query('search');

        // Build query - Kita hilangkan filter where('is_active', true)
        $query = Vote::withCount('options')
            ->orderBy('created_at', 'desc');

        // Apply search di title jika ada
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        // Paginate (misal 10 per halaman)
        $votes = $query->paginate(10);

        // Map data agar outputnya bersih dan konsisten
        $data = $votes->map(function ($vote) {
            return [
                'id' => $vote->id,
                'title' => $vote->title,
                'description' => $vote->description,
                'is_active' => (bool)$vote->is_active,
                'options_count' => $vote->options_count,
                'created_at' => $vote->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Daftar voting berhasil diambil',
            'data' => $data,
            'meta' => [
                'current_page' => $votes->currentPage(),
                'last_page' => $votes->lastPage(),
                'per_page' => $votes->perPage(),
                'total' => $votes->total(),
                'next_page_url' => $votes->nextPageUrl(),
                'prev_page_url' => $votes->previousPageUrl(),
            ],
        ]);
    }

    // 2. DETAIL VOTING
    public function show(Request $request, Vote $vote)
    {
        if (!$vote->is_active) {
            return response()->json(['status' => false, 'message' => 'Voting ini sudah ditutup'], 403);
        }

        $user = $request->user();
        $totalUsers = User::where('role', 'user')->count();

        // 1. Load options TANPA relasi user (karena kita sudah punya 'label')
        $vote->load(['options' => function ($query) {
            $query->orderBy('label', 'asc');
        }]);

        // 2. Hitung total suara
        $vote->loadCount('results');

        // 3. Cek status memilih
        $vote->is_voted = VoteResult::where('vote_id', $vote->id)
            ->where('user_id', $user->id)
            ->exists();

        $vote->participation_percentage = $totalUsers > 0
            ? round(($vote->results_count / $totalUsers) * 100, 1)
            : 0;

        // 4. Transformasi data agar rapi dan aman
        $options = $vote->options->map(function ($option) use ($vote) {
            $optionCount = VoteResult::where('vote_option_id', $option->id)->count();

            return [
                'id' => $option->id,
                'label' => $option->label, // Pakai label yang sudah ada
                'votes_count' => $optionCount,
                'percentage' => $vote->results_count > 0
                    ? round(($optionCount / $vote->results_count) * 100, 1)
                    : 0,
                'image_url' => $option->user && $option->user->image_path
                    ? asset('storage/' . $option->user->image_path)
                    : null,
            ];
        });

        // 5. Rakit response manual agar field start_at/end_at/user tidak muncul
        return response()->json([
            'status' => true,
            'message' => 'Detail voting berhasil diambil',
            'data' => [
                'id' => $vote->id,
                'title' => $vote->title,
                'description' => $vote->description,
                'results_count' => $vote->results_count,
                'is_voted' => $vote->is_voted,
                'participation_percentage' => $vote->participation_percentage,
                'options' => $options
            ]
        ]);
    }

    // 3. CAST VOTE
    public function store(Request $request)
    {
        $request->validate([
            'vote_id' => 'required|exists:votes,id',
            'vote_option_id' => 'required|exists:vote_options,id',
        ]);

        $vote = Vote::find($request->vote_id);

        // Hanya cek apakah is_active masih true
        if (!$vote || !$vote->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'Voting sudah tidak aktif atau ditutup.'
            ], 403);
        }

        $user = $request->user();

        $alreadyVoted = VoteResult::where('vote_id', $vote->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyVoted) {
            return response()->json([
                'status' => false,
                'message' => 'Anda sudah memberikan suara dalam voting ini.'
            ], 422);
        }

        VoteResult::create([
            'vote_id' => $vote->id,
            'vote_option_id' => $request->vote_option_id,
            'user_id' => $user->id
        ]);

        return response()->json(['status' => true, 'message' => 'Suara Anda berhasil dikirim!']);
    }
}
