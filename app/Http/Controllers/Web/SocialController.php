<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Social;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        $socials = Social::latest()->get();
        return view('pages.socials.index', compact('socials'));
    }

    public function create()
    {
        return view('pages.socials.create');
    }


    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'file' => 'required_without:image|file|mimes:pdf|max:512000',
                'image' => 'required_without:file|image|mimes:jpg,jpeg,png|max:5120',
            ],
            [
                'title.required' => 'Judul wajib diisi.',
                'title.max' => 'Judul maksimal 255 karakter.',
                'file.required_without' => 'Salah satu (File PDF atau Gambar) wajib diunggah.',
                'file.mimes' => 'File harus berupa PDF.',
                'file.max' => 'File maksimal 500MB.',
                'image.required_without' => 'Salah satu (File PDF atau Gambar) wajib diunggah.',
                'image.mimes' => 'Image harus JPG, JPEG, atau PNG.',
                'image.max' => 'Image maksimal 5MB.',
            ]
        );

        // Upload file PDF jika ada
        $filePath = $request->hasFile('file')
            ? $request->file('file')->store('social/files', 'public')
            : null;

        // Upload image jika ada
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('social/images', 'public')
            : null;

        $social = Social::create([
            'type' => 'social', // 👈 penting untuk feed / notif
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
                'Program Sosial', // TITLE
                $social->title, // BODY
                [
                    'id' => $social->id,
                    'type' => $social->type, // 👈 buat auto routing di mobile
                ]
            );
        }

        return redirect()->route('socials.index')->with('success', 'Program sosial berhasil dibuat dan notifikasi dikirim.');
    }

    public function show(Social $social)
    {
        return view('pages.socials.show', compact('social'));
    }

    public function edit(Social $social)
    {
        return view('pages.socials.edit', compact('social'));
    }

    public function update(Request $request, Social $social)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf|max:512000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ], [
            'title.required' => 'Judul wajib diisi.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'file.mimes' => 'File harus berupa PDF.',
            'file.max' => 'File maksimal 500MB.',
            'image.mimes' => 'Image harus JPG, JPEG, atau PNG.',
            'image.max' => 'Image maksimal 5MB.',
        ]);

        // Update PDF jika ada
        if ($request->hasFile('file')) {
            if ($social->file_path) {
                Storage::disk('public')->delete($social->file_path);
            }
            $social->file_path = $request->file('file')->store('social/files', 'public');
        }

        // Update Image jika ada
        if ($request->hasFile('image')) {
            if ($social->image_path) {
                Storage::disk('public')->delete($social->image_path);
            }
            $social->image_path = $request->file('image')->store('social/images', 'public');
        }

        $social->title = $request->title;
        $social->description = $request->description;
        $social->save();

        return redirect()->route('socials.index')->with('success', 'Program sosial berhasil diperbarui.');
    }

    public function destroy(Social $social)
    {
        if ($social->file_path) {
            Storage::disk('public')->delete($social->file_path);
        }
        if ($social->image_path) {
            Storage::disk('public')->delete($social->image_path);
        }

        $social->delete();

        return redirect()->route('socials.index')->with('success', 'Program sosial berhasil dihapus.');
    }
}
