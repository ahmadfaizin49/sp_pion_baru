<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Learning;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class LearningController extends Controller
{

    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        $learnings = Learning::latest()->get();
        return view('pages.learnings.index', compact('learnings'));
    }

    public function create()
    {
        return view('pages.learnings.create');
    }

    // public function store(Request $request)
    // {
    //     $request->validate(
    //         [
    //             'title' => 'required|string|max:255',
    //             'file' => 'required|file|mimes:pdf|max:10240',
    //         ],
    //         [
    //             'title.required' => 'Judul wajib diisi.',
    //             'title.max' => 'Judul maksimal 255 karakter.',
    //             'file.required' => 'File PDF wajib diunggah.',
    //             'file.mimes' => 'File harus berupa PDF.',
    //             'file.max' => 'File maksimal 10MB.',
    //         ]
    //     );

    //     $filePath = $request->file('file')->store('learning', 'public');

    //     Learning::create([
    //         'title' => $request->title,
    //         'file_path' => $filePath,
    //     ]);

    //     return redirect()->route('learnings.create')->with('success', 'Materi belajar berhasil dibuat.');
    // }

    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'file' => 'required|file|mimes:pdf|max:10240', // MAX 10MB
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // MAX 5MB
            ],
            [
                'title.required' => 'Judul wajib diisi.',
                'title.max' => 'Judul maksimal 255 karakter.',
                'file.required' => 'File PDF wajib diunggah.',
                'file.mimes' => 'File harus berupa PDF.',
                'file.max' => 'File maksimal 10MB.',
                'image.mimes' => 'Image harus JPG, JPEG, atau PNG.',
                'image.max' => 'Image maksimal 5MB.',
            ]
        );

        // Upload file PDF
        $filePath = $request->file('file')->store('learning/files', 'public');

        // Upload image jika ada
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('learning/images', 'public')
            : null;

        $learning = Learning::create([
            'type' => 'learning', // 👈 penting untuk feed / notif
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'image_path' => $imagePath,
        ]);

        // ---------- KIRIM NOTIF KE SEMUA USER ----------
        // $tokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
        $tokens = User::whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->filter(fn($t) => !empty($t)) // skip null / empty
            ->toArray();

        if (!empty($tokens)) {
            $this->firebase->sendToTokens(
                $tokens,
                'Materi Belajar', // TITLE
                $learning->title, // BODY
                [
                    'id' => $learning->id,
                    'type' => $learning->type, // 👈 buat auto routing di mobile
                ]
            );
        }

        return redirect()->route('learnings.index')->with('success', 'Materi belajar berhasil dibuat dan notifikasi dikirim.');
    }

    public function show(Learning $learning)
    {
        return view('pages.learnings.show', compact('learning'));
    }

    public function edit(Learning $learning)
    {
        return view('pages.learnings.edit', compact('learning'));
    }

    public function update(Request $request, Learning $learning)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf|max:10240',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ], [
            'title.required' => 'Judul wajib diisi.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'file.mimes' => 'File harus berupa PDF.',
            'file.max' => 'File maksimal 10MB.',
            'image.mimes' => 'Image harus JPG, JPEG, atau PNG.',
            'image.max' => 'Image maksimal 5MB.',
        ]);

        // Update PDF jika ada
        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($learning->file_path);
            $learning->file_path = $request->file('file')->store('learning/files', 'public');
        }

        // Update Image jika ada
        if ($request->hasFile('image')) {
            if ($learning->image_path) {
                Storage::disk('public')->delete($learning->image_path);
            }
            $learning->image_path = $request->file('image')->store('learning/images', 'public');
        }

        $learning->title = $request->title;
        $learning->description = $request->description;
        $learning->save();

        return redirect()->route('learnings.index')->with('success', 'Materi belajar berhasil diperbarui.');
    }

    public function destroy(Learning $learning)
    {
        Storage::disk('public')->delete($learning->file_path);
        if ($learning->image_path) {
            Storage::disk('public')->delete($learning->image_path);
        }

        $learning->delete();

        return redirect()->route('learnings.index')->with('success', 'Materi belajar berhasil dihapus.');
    }
}
