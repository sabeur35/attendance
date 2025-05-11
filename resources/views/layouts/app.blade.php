<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Attendance System') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Attendance System') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('courses.index') }}">Courses</a>
                        </li>
                        @if(auth()->user()->role === 'teacher' || auth()->user()->role === 'admin')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="sessionsDropdown" role="button" data-bs-toggle="dropdown">
                                    Sessions
                                </a>
                                <ul class="dropdown-menu">
                                    @if(auth()->user()->courses()->count() > 0)
                                        <li><a class="dropdown-item" href="#" onclick="showAddSessionModal()">Add New Session</a></li>
                                    @endif
                                    <li><a class="dropdown-item" href="{{ route('home') }}?tab=sessions">Manage Sessions</a></li>
                                </ul>
                            </li>
                        @endif
                        @if(auth()->user()->role === 'student')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('devices.index') }}">My Devices</a>
                            </li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav ms-auto mb-2 mb-md-0">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">sign in</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Add Session Modal -->
    @auth
        @if(auth()->user()->role === 'teacher' || auth()->user()->role === 'admin')
            <div class="modal fade" id="addSessionModal" tabindex="-1" aria-labelledby="addSessionModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addSessionModalLabel">Add New Session</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addSessionForm" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="course_id" class="form-label">Select Course</label>
                                    <select class="form-select" id="course_id" name="course_id" required>
                                        <option value="">Select a course</option>
                                    </select>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="start_time" class="form-label">Start Time</label>
                                        <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="end_time" class="form-label">End Time</label>
                                        <input type="datetime-local" class="form-control" id="end_time" name="end_time" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="latitude" class="form-label">Latitude</label>
                                        <input type="number" step="any" class="form-control" id="latitude" name="latitude" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="longitude" class="form-label">Longitude</label>
                                        <input type="number" step="any" class="form-control" id="longitude" name="longitude" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="radius_meters" class="form-label">Radius (meters)</label>
                                        <input type="number" class="form-control" id="radius_meters" name="radius_meters" min="10" max="1000" value="50" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <button type="button" class="btn btn-secondary" id="getCurrentLocation">Get Current Location</button>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submitSession">Create Session</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    @auth
        @if(auth()->user()->role === 'teacher' || auth()->user()->role === 'admin')
            <script>
                function showAddSessionModal() {
                    // Fetch user's courses
                    $.ajax({
                        url: '{{ route("courses.index") }}',
                        type: 'GET',
                        dataType: 'json',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            const courseSelect = $('#course_id');
                            courseSelect.empty();
                            courseSelect.append('<option value="">Select a course</option>');
                            
                            response.courses.data.forEach(function(course) {
                                courseSelect.append(`<option value="${course.id}">${course.name} (${course.code})</option>`);
                            });
                            
                            $('#addSessionModal').modal('show');
                        },
                        error: function(xhr) {
                            console.error('Error fetching courses:', xhr.responseText);
                            alert('Failed to load courses. Please try again.');
                        }
                    });
                }
                
                $(document).ready(function() {
                    // Get current location
                    $('#getCurrentLocation').click(function() {
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function(position) {
                                $('#latitude').val(position.coords.latitude);
                                $('#longitude').val(position.coords.longitude);
                            }, function(error) {
                                console.error('Error getting location:', error);
                                alert('Failed to get your location. Please enter coordinates manually.');
                            });
                        } else {
                            alert('Geolocation is not supported by this browser.');
                        }
                    });
                    
                    // Submit session form
                    $('#submitSession').click(function() {
                        const courseId = $('#course_id').val();
                        if (!courseId) {
                            alert('Please select a course');
                            return;
                        }
                        
                        const formData = $('#addSessionForm').serialize();
                        
                        $.ajax({
                            url: `/courses/${courseId}/sessions`,
                            type: 'POST',
                            data: formData,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                $('#addSessionModal').modal('hide');
                                window.location.href = response.redirect || `/courses/${courseId}/sessions`;
                            },
                            error: function(xhr) {
                                console.error('Error creating session:', xhr.responseText);
                                let errorMessage = 'Failed to create session. Please check your inputs.';
                                
                                if (xhr.responseJSON && xhr.responseJSON.errors) {
                                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                                }
                                
                                alert(errorMessage);
                            }
                        });
                    });
                });
            </script>
        @endif
    @endauth
    
    @yield('scripts')
</body>
</html>
