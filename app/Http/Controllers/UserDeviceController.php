<?php

namespace App\Http\Controllers;

use App\Models\UserDevice;
use Illuminate\Http\Request;

class UserDeviceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $devices = auth()->user()->devices;

        return view('devices.index', compact('devices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mac_address' => 'required|string|max:255',
            'device_name' => 'required|string|max:255',
        ]);

        // Check if device already exists
        $existingDevice = UserDevice::where('mac_address', $validated['mac_address'])
            ->where('user_id', '!=', auth()->id())
            ->first();

        if ($existingDevice) {
            return redirect()->back()->with('error', 'This MAC address is already registered to another user.');
        }

        $device = auth()->user()->devices()->updateOrCreate(
            ['mac_address' => $validated['mac_address']],
            [
                'device_name' => $validated['device_name'],
                'is_verified' => true,
                'verified_at' => now(),
            ]
        );

        return redirect()->route('devices.index')
            ->with('success', 'Device registered successfully.');
    }

    public function destroy(UserDevice $device)
    {
        if ($device->user_id !== auth()->id()) {
            abort(403);
        }

        $device->delete();

        return redirect()->route('devices.index')
            ->with('success', 'Device removed successfully.');
    }
}
