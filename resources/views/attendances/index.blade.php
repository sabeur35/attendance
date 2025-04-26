@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>{{ $session->course->name }} ({{ $session->course->code }})</h2>
            <h4>Attendance Details: {{ $session->start_time->format('M d, Y - h:i A') }}</h4>
            <a href="{{ route('courses.sessions.show', [$session->course_id, $session]) }}" class="btn btn-secondary">Back to Session</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Attendance List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Check-in Time</th>
                                    <th>Status</th>
                                    <th>Device MAC</th>
                                    <th>Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->user->name }}</td>
                                    <td>{{ $attendance->user->email }}</td>
                                    <td>{{ $attendance->check_in_time->format('h:i:s A') }}</td>
                                    <td>
                                        @if($attendance->is_verified)
                                            <span class="badge bg-success">Verified</span>
                                        @else
                                            <span class="badge bg-danger">Failed</span>
                                            <span class="d-block small text-muted mt-1">{{ $attendance->verification_message }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $attendance->mac_address }}</small>
                                    </td>
                                    <td>
                                        <a href="https://www.google.com/maps?q={{ $attendance->latitude }},{{ $attendance->longitude }}" 
                                           target="_blank" class="btn btn-sm btn-outline-secondary">
                                            View Map
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No attendance records found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
