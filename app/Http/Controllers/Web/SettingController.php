<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::all()->keyBy('key');
        return view('pages.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            // Jika nilainya array (contoh: dasar_hukum), simpan sebagai JSON
            if (is_array($value)) {
                // Hapus item kosong
                $value = array_values(array_filter($value, fn($v) => trim($v) !== ''));
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            }
            Setting::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->route('settings.edit')->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
