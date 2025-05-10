<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ClassSessionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserDeviceController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

// Admin Registration Routes
Route::get('/register/admin', [App\Http\Controllers\Auth\AdminRegisterController::class, 'showRegistrationForm'])->name('admin.register.form');
Route::post('/register/admin', [App\Http\Controllers\Auth\AdminRegisterController::class, 'register'])->name('admin.register');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    // Courses
    Route::resource('courses', CourseController::class);
    Route::post('/courses/{course}/add-student', [CourseController::class, 'addStudent'])->name('courses.add-student');
    Route::delete('/courses/{course}/students/{student}', [CourseController::class, 'removeStudent'])->name('courses.remove-student');

    // Class Sessions
    Route::get('/courses/{course}/sessions', [ClassSessionController::class, 'index'])->name('courses.sessions.index');
    Route::get('/courses/{course}/sessions/create', [ClassSessionController::class, 'create'])->name('courses.sessions.create');
    Route::post('/courses/{course}/sessions', [ClassSessionController::class, 'store'])->name('courses.sessions.store');
    Route::get('/courses/{course}/sessions/{session}', [ClassSessionController::class, 'show'])->name('courses.sessions.show');

    // Attendance
    Route::get('/attend/{secret}', [ClassSessionController::class, 'attend'])->name('attend');
    Route::post('/sessions/{session}/attend', [AttendanceController::class, 'store'])->name('sessions.attend.store');
    Route::get('/sessions/{session}/attendances', [AttendanceController::class, 'index'])->name('sessions.attendances.index');

    // User Devices
    Route::get('/devices', [UserDeviceController::class, 'index'])->name('devices.index');
    Route::post('/devices', [UserDeviceController::class, 'store'])->name('devices.store');
    Route::delete('/devices/{device}', [UserDeviceController::class, 'destroy'])->name('devices.destroy');
});
