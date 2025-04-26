<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassSession;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student')->only(['store']);
    }

    public function store(Request $request, ClassSession $session)
    {
        $student = auth()->user();

        // Validate request
        $validated = $request->validate([
            'mac_address' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Check if already attended
        $existingAttendance = Attendance::where('user_id', $student->id)
            ->where('class_session_id', $session->id)
            ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'You have already checked in to this session.');
        }

        // Create attendance record
        $attendance = Attendance::create([
            'user_id' => $student->id,
            'class_session_id' => $session->id,
            'check_in_time' => now(),
            'mac_address' => $validated['mac_address'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        // Verify attendance
        $isVerified = $attendance->verifyAttendance();

        if ($isVerified) {
            return redirect()->route('courses.show', $session->course_id)
                ->with('success', 'Attendance recorded successfully.');
        } else {
            return redirect()->route('courses.show', $session->course_id)
                ->with('warning', 'Attendance recorded but verification failed: ' . $attendance->verification_message);
        }
    }

    public function index(ClassSession $session)
    {
        $this->authorize('viewAttendance', $session->course);

        $attendances = $session->attendances()
            ->with('user')
            ->orderBy('check_in_time')
            ->paginate(20);

        return view('attendances.index', compact('session', 'attendances'));
    }
}
