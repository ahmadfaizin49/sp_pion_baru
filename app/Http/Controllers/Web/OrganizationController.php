<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        $organizations = Organization::latest()->get();
        return view('pages.organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('pages.organizations.create');
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

    //     $filePath = $request->file('file')->store('organization', 'public');

    //     Organization::create([
    //         'title' => $request->title,
    //         'file_path' => $filePath,
    //     ]);

    //     return redirect()->route('organizations.create')->with('success', 'Struktur organisasi berhasil dibuat.');
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
        $filePath = $request->file('file')->store('organization/files', 'public');

        // Upload image jika ada
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('organization/images', 'public')
            : null;

        $organization = Organization::create([
            'type' => 'organization', // 👈 penting untuk feed / notif
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
                'Struktur Organisasi', // TITLE
                $organization->title, // BODY
                [
                    'id' => $organization->id,
                    'type' => $organization->type, // 👈 buat auto routing di mobile
                ]
            );
        }

        return redirect()->route('organizations.index')->with('success', 'Struktur organisasi berhasil dibuat dan notifikasi dikirim.');
    }

    public function show(Organization $organization)
    {
        return view('pages.organizations.show', compact('organization'));
    }

    public function edit(Organization $organization)
    {
        return view('pages.organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
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
            Storage::disk('public')->delete($organization->file_path);
            $organization->file_path = $request->file('file')->store('organization/files', 'public');
        }

        // Update Image jika ada
        if ($request->hasFile('image')) {
            if ($organization->image_path) {
                Storage::disk('public')->delete($organization->image_path);
            }
            $organization->image_path = $request->file('image')->store('organization/images', 'public');
        }

        $organization->title = $request->title;
        $organization->description = $request->description;
        $organization->save();

        return redirect()->route('organizations.index')->with('success', 'Struktur organisasi berhasil diperbarui.');
    }

    public function destroy(Organization $organization)
    {
        Storage::disk('public')->delete($organization->file_path);
        if ($organization->image_path) {
            Storage::disk('public')->delete($organization->image_path);
        }

        $organization->delete();

        return redirect()->route('organizations.index')->with('success', 'Struktur organisasi berhasil dihapus.');
    }
}
