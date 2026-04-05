<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\UserDevice;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = UserDevice::with('user')->latest()->get();
        return view('pages.devices.index', compact('devices'));
    }

    public function destroy(UserDevice $device)
    {
        $userName = $device->user->name ?? 'User';
        $device->delete();
        return redirect()->route('devices.index')->with('success', "Akses perangkat untuk user $userName berhasil di-reset.");
    }
}
