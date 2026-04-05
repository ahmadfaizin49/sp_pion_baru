<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Vote;
use App\Models\User;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        $votes = Vote::withCount('options')->latest()->get();
        return view('pages.votes.index', compact('votes'));
    }

    public function create()
    {
        $users = User::where('role', 'user')
            ->orderBy('name', 'asc')
            ->get();

        return view('pages.votes.create', compact('users'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'options' => 'required|array|min:2|max:8',
            'options.*' => 'exists:users,id',
            'visions' => 'nullable|array',
            'visions.*' => 'nullable|string',
        ], [
            'title.required' => 'Judul voting wajib diisi.',
            'title.max' => 'Judul voting maksimal 255 karakter.',
            'options.required' => 'Minimal pilih 2 kandidat.',
            'options.array' => 'Format kandidat tidak valid.',
            'options.min' => 'Voting harus memiliki minimal 2 kandidat.',
            'options.max' => 'Voting maksimal memiliki 8 kandidat.',
            'options.*.exists' => 'Salah satu kandidat tidak ditemukan.',
        ]);

        DB::transaction(function () use ($request) {

            $vote = Vote::create([
                'title' => $request->title,
                'description' => $request->description,
                'is_active'   => true
            ]);

            foreach ($request->options as $userId) {
                $user = User::find($userId);
                $vote->options()->create([
                    'user_id' => $user->id,
                    'label' => $user->name,
                    'vision' => $request->visions[$userId] ?? null,
                ]);
            }

            // ---------- KIRIM NOTIF KE SEMUA USER ----------
            DB::afterCommit(function () use ($vote) {
                // $tokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
                $tokens = User::whereNotNull('fcm_token')
                    ->pluck('fcm_token')
                    ->filter(fn($t) => !empty($t)) // skip null / empty
                    ->toArray();

                if (!empty($tokens)) {
                    app(FirebaseService::class)->sendToTokens(
                        $tokens,
                        'Voting',
                        $vote->title
                    );
                }
            });
        });

        return redirect()->route('votes.index')->with('success', 'Voting berhasil dibuat dan notifikasi dikirim.');
    }

    public function show(Vote $vote)
    {
        // Hitung suara per opsi secara otomatis dan load relasi user (kandidat)
        $vote->load(['options' => function ($query) {
            $query->with(['user'])->withCount('results')->orderBy('label', 'asc');
        }]);

        return view('pages.votes.show', compact('vote'));
    }

    public function getResults(Vote $vote)
    {
        $vote->load(['options' => function ($query) {
            $query->withCount('results')->orderBy('label', 'asc');
        }]);

        $totalVotesCount = $vote->options->sum('results_count');
        $totalEligibleUsers = User::where('role', 'user')->count();
        $participationRate = $totalEligibleUsers > 0
            ? round(($totalVotesCount / $totalEligibleUsers) * 100, 1)
            : 0;

        $options = $vote->options->map(function ($option) use ($totalVotesCount) {
            $optionPercentage = $totalVotesCount > 0
                ? round(($option->results_count / $totalVotesCount) * 100, 1)
                : 0;
            return [
                'id' => $option->id,
                'results_count' => $option->results_count,
                'percentage' => $optionPercentage,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'options' => $options,
                'total_votes_count' => $totalVotesCount,
                'total_eligible_users' => $totalEligibleUsers,
                'participation_rate' => $participationRate,
            ]
        ]);
    }

    public function edit(Vote $vote)
    {
        // Load vote + options A-Z
        $vote->load(['options' => function ($query) {
            $query->orderBy('label', 'asc');
        }]);

        return view('pages.votes.edit', compact('vote'));
    }

    public function update(Request $request, Vote $vote)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'visions' => 'nullable|array',
            'visions.*' => 'nullable|string',
        ], [
            'title.required' => 'Judul voting wajib diisi.',
            'title.max' => 'Judul voting maksimal 255 karakter.',
        ]);

        DB::transaction(function () use ($request, $vote) {
            $vote->update([
                'title' => $request->title,
                'description' => $request->description,
                'is_active' => $request->has('is_active') ? $request->is_active : $vote->is_active,
            ]);

            if ($request->has('visions')) {
                foreach ($request->visions as $optionId => $vision) {
                    $vote->options()->where('id', $optionId)->update([
                        'vision' => $vision
                    ]);
                }
            }
        });

        return redirect()->route('votes.index')->with('success', 'Voting berhasil diperbarui.');
    }


    public function destroy(Vote $vote)
    {
        $vote->options()->delete();
        $vote->delete();
        return redirect()->route('votes.index')->with('success', 'Voting berhasil dihapus.');
    }
}
