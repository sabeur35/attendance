@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>{{ $course->name }} ({{ $course->code }})</h2>
            <h4>Class Session: {{ $session->start_time->format('M d, Y - h:i A') }} to {{ $session->end_time->format('h:i A') }}</h4>
            <a href="{{ route('courses.sessions.index', $course) }}" class="btn btn-secondary">Back to Sessions</a>
        </div>
    </div>

    <div class="row">
        @if(auth()->user()->role === 'teacher' || auth()->user()->role === 'admin')
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Attendance QR Code</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div id="qrcode"></div>
                    </div>
                    <p class="mb-3">Session Time: {{ $session->start_time->format('M d, Y - h:i A') }} to {{ $session->end_time->format('h:i A') }}</p>
                    <p>Have students scan this QR code to check in</p>
                    <button class="btn btn-success" onclick="window.print()">Print QR Code</button>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Attendance Summary</h5>
                </div>
                <div class="card-body">
                    <h6>Total Students: {{ $course->students->count() }}</h6>
                    <h6>Checked In: {{ $attendances->count() }}</h6>
                    <h6>Verified Attendances: {{ $attendances->where('is_verified', true)->count() }}</h6>
                    
                    <hr>
                    
                    <h6>Attendance List</h6>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Check-in Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->user->name }}</td>
                                    <td>{{ $attendance->check_in_time->format('h:i A') }}</td>
                                    <td>
                                        @if($attendance->is_verified)
                                            <span class="badge bg-success">Verified</span>
                                        @else
                                            <span class="badge bg-danger" title="{{ $attendance->verification_message }}">Failed</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No attendances recorded yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <a href="{{ route('sessions.attendances.index', $session) }}" class="btn btn-primary">
                        View Full Attendance Details
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Attendance</h5>
                </div>
                <div class="card-body">
                    @php
                        $existingAttendance = $attendances->where('user_id', auth()->id())->first();
                    @endphp
                    
                    @if($existingAttendance)
                        <div class="alert alert-info">
                            <h5>You have checked in to this session!</h5>
                            <p>Check-in time: {{ $existingAttendance->check_in_time->format('M d, Y - h:i A') }}</p>
                            <p>
                                Status: 
                                @if($existingAttendance->is_verified)
                                    <span class="badge bg-success">Verified</span>
                                @else
                                    <span class="badge bg-danger">Verification Failed: {{ $existingAttendance->verification_message }}</span>
                                @endif
                            </p>
                        </div>
                    @else
                        <p>You haven't checked in to this session yet.</p>
                        <a href="{{ $session->getQrCodeUrl() }}" class="btn btn-success">Check In Now</a>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var qrCodeContainer = document.getElementById('qrcode');
        if (qrCodeContainer) {
            new QRCode(qrCodeContainer, {
                text: "{{ $session->getQrCodeUrl() }}",
                width: 300,
                height: 300,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        }
    });
</script>
@endsection
