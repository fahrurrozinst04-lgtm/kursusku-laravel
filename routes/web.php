<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\HomeController;
use App\Models\Course;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('enrollments', EnrollmentController::class)
    ->only(['index', 'create', 'store']);

Route::resource('courses', CourseController::class);

Route::get('/availability/{course}', function (Course $course) {
    return response()->json([
        'id' => $course->id,
        'code' => $course->code,
        'name' => $course->name,
        'quota' => $course->quota,
        'status' => $course->status,
    ]);
})->name('courses.availability');
