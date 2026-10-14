<?php

use App\Http\Middleware\CheckBlockedUser;
use App\Http\Middleware\RoleMiddleware;
use App\Livewire\Admin\AnnouncementIndex;
use App\Livewire\Admin\ClassroomIndex;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\StudentIndex;
use App\Livewire\Admin\SubjectIndex;
use App\Livewire\Admin\TeacherIndex;
use App\Http\Controllers\ImpersonateController;
use App\Livewire\Teacher\AssignmentIndex;
use App\Livewire\Teacher\ExamIndex;
use App\Livewire\Teacher\MaterialIndex;
use App\Livewire\Teacher\QuestionIndex;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', CheckBlockedUser::class, RoleMiddleware::class . ':admin'])
  ->prefix('admin')
  ->name('admin.')
  ->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/teachers', TeacherIndex::class)->name('teachers.index');
    Route::get('/students', StudentIndex::class)->name('students.index');
    Route::get('/classrooms', ClassroomIndex::class)->name('classrooms.index');
    Route::get('/subjects', SubjectIndex::class)->name('subjects.index');
    Route::get('/announcements', AnnouncementIndex::class)->name('announcements.index');
    Route::get('/materials', MaterialIndex::class)->name('materials.index');
    Route::get('/assignments', AssignmentIndex::class)->name('assignments.index');
    Route::get('/exams', ExamIndex::class)->name('exams.index');
    Route::get('/questions', QuestionIndex::class)->name('questions.index');

    Route::get('/impersonate/{userId}', [ImpersonateController::class, 'start'])->name('impersonate.start');
  });

Route::middleware(['auth', CheckBlockedUser::class])
  ->prefix('admin')
  ->name('admin.')
  ->group(function () {
    Route::get('/stop-impersonate', [ImpersonateController::class, 'stop'])->name('impersonate.stop');
  });
