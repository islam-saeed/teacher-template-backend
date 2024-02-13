<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    //
    // public function __construct() {
    //     $this->middleware('jwt.verify', ['except' => ['login', 'register']]);
    // }

    public function index(){
        $courses=Course::Get();
        return $courses;
    }

    public function course_list($id){


        $admin = Admin::find($id);
        
        if ($admin) {
            $courses = $admin->courses;
            return $courses;

        } else {
            return response()->json(['error' => ' المدرس غير موجود']);
            // المشرف غير موجود
        }   

        
    }

}
