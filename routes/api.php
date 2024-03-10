<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\HistoryController;
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
    Route::get('/groups',[SectionController::class,'groups_of_admin']);
    Route::post('/fees/{id}', [SectionController::class, 'fees']);

    

});

Route::group([
    'middleware' => ['assign.guard:admin'],
    'prefix' => 'student'
], function () {

    Route::get('/index',[StudentController::class,'index']);
    Route::post('/create', [StudentController::class, 'create']);
    Route::get('/all_students',[StudentController::class,'all_students']);
    Route::delete('/delete_student/{id}', [StudentController::class, 'delete_student']);
    Route::patch('/update_student/{id}', [StudentController::class, 'update_student']);
    Route::patch('/attendance/{id}', [StudentController::class, 'attendance']);

    

    Route::get('/student-profile', [StudentController::class, 'studentProfile']);    
});

Route::group([
    'middleware' => ['assign.guard:admin'],
    'prefix' => 'history'
], function () {

    Route::get('/show',[HistoryController::class,'show']);
    

});