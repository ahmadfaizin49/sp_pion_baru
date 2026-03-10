<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Information;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }


    public function index()
    {
        $informations = Information::latest()->get();
        return view('pages.informations.index', compact('informations'));
    }

    public function create()
    {
        return view('pages.informations.create');
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
        $filePath = $request->file('file')->store('information/files', 'public');

        // Upload image jika ada
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('information/images', 'public')
            : null;

        // Create Information + type otomatis
        $information = Information::create([
            'type' => 'information', // 👈 penting untuk feed / notif
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
                'Informasi', // TITLE
                $information->title, // BODY
                [
                    'id' => $information->id,
                    'type' => $information->type, // 👈 buat auto routing di mobile
                ]
            );
        }

        return redirect()->route('informations.index')->with('success', 'Informasi berhasil dibuat dan notifikasi dikirim.');
    }

    public function show(Information $information)
    {
        return view('pages.informations.show', compact('information'));
    }

    public function edit(Information $information)
    {
        return view('pages.informations.edit', compact('information'));
    }

    public function update(Request $request, Information $information)
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
            Storage::disk('public')->delete($information->file_path);
            $information->file_path = $request->file('file')->store('information/files', 'public');
        }

        // Update Image jika ada
        if ($request->hasFile('image')) {
            if ($information->image_path) {
                Storage::disk('public')->delete($information->image_path);
            }
            $information->image_path = $request->file('image')->store('information/images', 'public');
        }

        $information->title = $request->title;
        $information->description = $request->description;
        $information->save();

        return redirect()->route('informations.index')->with('success', 'Informasi berhasil diperbarui.');
    }

    public function destroy(Information $information)
    {
        Storage::disk('public')->delete($information->file_path);
        if ($information->image_path) {
            Storage::disk('public')->delete($information->image_path);
        }

        $information->delete();

        return redirect()->route('informations.index')->with('success', 'Informasi berhasil dihapus.');
    }
}
