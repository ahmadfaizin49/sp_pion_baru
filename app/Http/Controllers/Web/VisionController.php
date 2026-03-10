<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Vision;
use Illuminate\Http\Request;

class VisionController extends Controller
{
    public function edit()
    {
        $vision = Vision::first();
        return view('pages.vision.edit', compact('vision'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'subtitle' => 'required|string',
        ]);

        $vision = Vision::first();

        $vision->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
        ]);

        return back()->with('success', 'Visi Misi berhasil diperbarui.');
    }
}
