<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Union;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class UnionController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }


    public function index()
    {
        $unions = Union::latest()->get();
        return view('pages.unions.index', compact('unions'));
    }

    public function create()
    {
        return view('pages.unions.create');
    }

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
        $filePath = $request->file('file')->store('union/files', 'public');

        // Upload image jika ada
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('union/images', 'public')
            : null;

        // Create Union + type otomatis
        $union = Union::create([
            'type' => 'union', // 👈 penting untuk feed / notif
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
                'Profil Serikat', // TITLE
                $union->title, // BODY
                [
                    'id' => $union->id,
                    'type' => $union->type, // 👈 buat auto routing di mobile
                ]
            );
        }

        return redirect()->route('unions.index')->with('success', 'Profile serikat berhasil dibuat dan notifikasi dikirim.');
    }

    public function show(Union $union)
    {
        return view('pages.unions.show', compact('union'));
    }

    public function edit(Union $union)
    {
        return view('pages.unions.edit', compact('union'));
    }

    public function update(Request $request, Union $union)
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
            Storage::disk('public')->delete($union->file_path);
            $union->file_path = $request->file('file')->store('union/files', 'public');
        }

        // Update Image jika ada
        if ($request->hasFile('image')) {
            if ($union->image_path) {
                Storage::disk('public')->delete($union->image_path);
            }
            $union->image_path = $request->file('image')->store('union/images', 'public');
        }

        $union->title = $request->title;
        $union->description = $request->description;
        $union->save();

        return redirect()->route('unions.index')->with('success', 'Profile serikat berhasil diperbarui.');
    }

    public function destroy(Union $union)
    {
        Storage::disk('public')->delete($union->file_path);
        if ($union->image_path) {
            Storage::disk('public')->delete($union->image_path);
        }

        $union->delete();

        return redirect()->route('unions.index')->with('success', 'Profile serikat berhasil dihapus.');
    }
}
