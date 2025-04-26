@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>{{ $course->name }} ({{ $course->code }})</h2>
            <p class="lead">{{ $course->description }}</p>
            <p><strong>Teacher:</strong> {{ $course->teacher->name }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('courses.index') }}" class="btn btn-secondary me-2">Back to Courses</a>
            @can('update', $course)
            <a href="{{ route('courses.edit', $course) }}" class="btn btn-warning">Edit Course</a>
            @endcan
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Class Sessions</h5>
                    <div>
                        @can('update', $course)
                        <a href="{{ route('courses.sessions.create', $course) }}" class="btn btn-sm btn-light">New Session</a>
                        @endcan
                        <a href="{{ route('courses.sessions.index', $course) }}" class="btn btn-sm btn-outline-light">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Attendance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sessions as $session)
                                <tr>
                                    <td>{{ $session->start_time->format('M d, Y') }}</td>
                                    <td>{{ $session->start_time->format('h:i A') }} - {{ $session->end_time->format('h:i A') }}</td>
                                    <td>
                                        @if(auth()->user()->role === 'student')
                                            @php
                                                $attended = $session->attendances->where('user_id', auth()->id())->first();
                                            @endphp
                                            @if($attended)
                                                @if($attended->is_verified)
                                                    <span class="badge bg-success">Present</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Present (Unverified)</span>
                                                @endif
                                            @else
                                                <span class="badge bg-danger">Absent</span>
                                            @endif
                                        @else
                                            {{ $session->attendances->count() }} / {{ $course->students->count() }}
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('courses.sessions.show', [$course, $session]) }}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No sessions found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('update', $course)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Enrolled Students</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('courses.add-student', $course) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="input-group">
                            <select name="student_id" class="form-select" required>
                                <option value="">Select a student to add...</option>
                                @foreach(App\Models\User::where('role', 'student')->whereDoesntHave('courses', function($query) use ($course) {
                                    $query->where('course_id', $course->id);
                                })->get() as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-outline-primary">Add Student</button>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Registered Devices</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                <tr>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->devices->where('is_verified', true)->count() }}</td>
                                    <td>
                                        <form action="{{ route('courses.remove-student', [$course, $student]) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to remove this student from the course?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No students enrolled yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection
