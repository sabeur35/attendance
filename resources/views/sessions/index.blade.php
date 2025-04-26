@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>{{ $course->name }} ({{ $course->code }})</h2>
            <h4>Class Sessions</h4>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('courses.show', $course) }}" class="btn btn-secondary me-2">Back to Course</a>
            @can('update', $course)
            <a href="{{ route('courses.sessions.create', $course) }}" class="btn btn-primary">New Session</a>
            @endcan
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
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
                    
                    {{ $sessions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
