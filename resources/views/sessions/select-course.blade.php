@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Select Course for New Session</h4>
                </div>
                <div class="card-body">
                    @if($courses->isEmpty())
                        <div class="alert alert-warning">
                            <p>You don't have any courses available. Please create a course first.</p>
                            <a href="{{ route('courses.create') }}" class="btn btn-primary mt-2">Create New Course</a>
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($courses as $course)
                                <a href="{{ route('courses.sessions.create', $course) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1">{{ $course->name }}</h5>
                                        <p class="mb-1 text-muted">{{ $course->code }}</p>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">Select</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                    
                    <div class="mt-3">
                        <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">Back to Courses</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
