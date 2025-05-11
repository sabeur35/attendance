<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:teacher,admin')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->role === 'student') {
            $courses = auth()->user()->courses()->paginate(10);
        } elseif (auth()->user()->role === 'teacher') {
            $courses = auth()->user()->taughtCourses()->paginate(10);
        } else {
            $courses = Course::paginate(10);
        }

        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = User::where('role', 'teacher')->get();

        return view('courses.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:courses',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher_id' => 'required|exists:users,id',
        ]);

        $course = Course::create($validated);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Course created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        if (auth()->user()->role === 'student') {
            $this->authorize('attend', $course);
        }

        $sessions = $course->sessions()
            ->orderBy('start_time', 'desc')
            ->paginate(5);

        $students = $course->students()->paginate(20);

        return view('courses.show', compact('course', 'sessions', 'students'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $this->authorize('update', $course);

        $teachers = User::where('role', 'teacher')->get();

        return view('courses.edit', compact('course', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:courses,code,' . $course->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher_id' => 'required|exists:users,id',
        ]);

        $course->update($validated);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Course updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        
        // Detach all students from the course
        $course->students()->detach();
        
        // Delete all associated sessions (if needed)
        $course->sessions()->delete();
        
        // Now delete the course
        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully');
    }

    public function addStudent(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $student = User::findOrFail($validated['student_id']);

        if ($student->role !== 'student') {
            return redirect()->back()->with('error', 'Selected user is not a student');
        }

        $course->students()->syncWithoutDetaching([$student->id]);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Student added to course');
    }

    public function removeStudent(Course $course, User $student)
    {
        $this->authorize('update', $course);

        $course->students()->detach($student->id);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Student removed from course');
    }
}
