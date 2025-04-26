<?php

namespace App\Http\Controllers;

use App\Models\ClassSession;
use App\Models\Course;
use Illuminate\Http\Request;

class ClassSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:teacher,admin')->except(['show', 'attend']);
    }

    public function index(Course $course)
    {
        $this->authorize('view', $course);

        $sessions = $course->sessions()->orderBy('start_time', 'desc')->paginate(10);

        return view('sessions.index', compact('course', 'sessions'));
    }

    public function create(Course $course)
    {
        $this->authorize('update', $course);

        return view('sessions.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_meters' => 'required|integer|min:10|max:1000',
        ]);

        $session = $course->sessions()->create([
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'radius_meters' => $validated['radius_meters'],
            'qr_code_secret' => md5($course->id . time() . rand(1000, 9999)),
        ]);

        return redirect()->route('courses.sessions.show', [$course, $session])
            ->with('success', 'Class session created successfully');
    }

    public function show(Course $course, ClassSession $session)
    {
        if (auth()->user()->role === 'student') {
            $this->authorize('attend', $course);
        } else {
            $this->authorize('view', $course);
        }

        $attendances = $session->attendances()->with('user')->get();

        return view('sessions.show', compact('course', 'session', 'attendances'));
    }

    public function attend($secret)
    {
        $session = ClassSession::where('qr_code_secret', $secret)->firstOrFail();

        if (auth()->user()->role !== 'student') {
            return redirect()->route('courses.sessions.show', [$session->course_id, $session->id]);
        }

        return view('sessions.attend', compact('session'));
    }
}
