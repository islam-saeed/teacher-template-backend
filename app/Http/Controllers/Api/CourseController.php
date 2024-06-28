<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    
    public function __construct() {
        $this->middleware('jwt.verify', ['except' => ['index']]);
    }

    public function index(){
        $courses = Course::with('media')->get();

        return $courses;
    }


    public function create(Request $request) {
        // Get the current admin ID
        $adminId = Auth::id();

        // Add 'admin_id' and set it to the current admin ID
        $request->merge(['admin_id' => $adminId]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:courses',
            'price' => 'string',
            'description' => 'string',
            'card_media' => 'required|string',
            'admin_id' =>'required',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $course = Course::create($validator->validated());

        return response()->json([
            'message' => 'Admin successfully create course',
            'course' => $course
        ], 201);
    }

    public function all_courses(){

        // Get the current admin ID
        $adminId = Auth::id();

        $admin = Admin::find($adminId);

        $courses = $admin->courses;
        return response()->json($courses);

        
    }

    public function delete_course($id){
        $courseId = $id;
        // Get the current admin ID
        $adminId = Auth::id();
    
        // Get the admin model
        $admin = Admin::find($adminId);
    
        // Get the model of the specified department
        $course = Course::find($courseId);
    
        // Check the existence of the department
        if (!$course) {
            return response()->json([
                'error' => 'course not found'
            ], 404);
        }
    
        // Check if the course belongs to the admin
        if ($course->admin_id != $admin->id) {
            return response()->json([
                'message' => 'Unauthorized action'
            ], 403);
        }
    
        // Delete course
        $course->delete();
    
        return response()->json([
            'message' => 'Course deleted successfully'
        ], 201);
    }
    

    public function update_course($id, Request $request){
        $courseId = $id;
        // Get the current admin ID
        $adminId = Auth::id();
    
        // Find the admin
        $admin = Admin::find($adminId);
    
        // Find the course
        $course = Course::find($courseId);
    
        // Check the existence of the course
        if (!$course) {
            return response()->json(['error' => 'Course not found']);
        }
    
        // Check if the course belongs to the admin
        if ($course->admin_id != $admin->id) {
            return response()->json(['message' => 'Unauthorized action']);
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:courses',
            'price' => 'required|string',
            'description' => 'required|string',
            'card_media' => 'required|string',
        ]);
    
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }    
    
        // Update the course with the new data
        $course->update($validator->validated());
    
        return response()->json([
            'message' => 'Course updated successfully',
            'course' => $course
        ]);
    }
    

}
