<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group([
    'middleware' => ['assign.guard:admin'],
    'prefix' => 'admin'
], function () {

    Route::get('/index',[AdminController::class,'index']);
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/register', [AdminController::class, 'register']);
    Route::post('/logout', [AdminController::class, 'logout']);
    Route::post('/refresh', [AdminController::class, 'refresh']);
    Route::get('/admin-profile', [AdminController::class, 'adminProfile']);    
});
Route::group([
    'middleware' => ['assign.guard:admin'],
    'prefix' => 'section'
], function () {

    Route::get('/index',[SectionController::class,'index']);
    Route::post('/create', [SectionController::class, 'create']);
    Route::get('/all_section',[SectionController::class,'all_section']);
    Route::delete('/delete_section/{id}', [SectionController::class, 'delete_section']);
    Route::patch('/update_section/{id}', [SectionController::class, 'update_section']);
});

Route::group([
    'middleware' => ['assign.guard:student'],
    'prefix' => 'student'
], function () {

    Route::get('/index',[StudentController::class,'index']);

    Route::post('/login', [StudentController::class, 'login']);
    Route::post('/register', [StudentController::class, 'register']);
    Route::post('/logout', [StudentController::class, 'logout']);
    Route::post('/refresh', [StudentController::class, 'refresh']);
    Route::get('/student-profile', [StudentController::class, 'studentProfile']);    
});

Route::group([
    'middleware' => 'assign.guard:admin',
    'prefix' => 'course'
], function () {

    Route::get('/index',[CourseController::class,'index']);
    Route::get('/course_list{id}',[CourseController::class,'course_list']);


    // Route::post('/login', [StudentController::class, 'login']);
    // Route::post('/register', [StudentController::class, 'register']);
    // Route::post('/logout', [StudentController::class, 'logout']);
    // Route::post('/refresh', [StudentController::class, 'refresh']);
    // Route::get('/student-profile', [StudentController::class, 'studentProfile']);    
});