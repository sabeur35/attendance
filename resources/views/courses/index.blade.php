@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Courses</h2>
        </div>
        <div class="col-md-4 text-end">
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
            <a href="{{ route('courses.create') }}" class="btn btn-primary">Create New Course</a>
            @endif
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
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Teacher</th>
                                    <th>Students</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($courses as $course)
                                <tr>
                                    <td>{{ $course->code }}</td>
                                    <td>{{ $course->name }}</td>
                                    <td>{{ $course->teacher->name }}</td>
                                    <td>{{ $course->students->count() }}</td>
                                    <td>
                                        <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-info">View</a>
                                        @can('update', $course)
                                        <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-warning">Edit</a>
                                        @endcan
                                        @can('delete', $course)
                                        <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this course?')">Delete</button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No courses found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $courses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
