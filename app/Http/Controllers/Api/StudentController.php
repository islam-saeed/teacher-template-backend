<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('jwt.verify', ['except' => ['index']]);
    }

    public function index(){
        $student = Student::get();
        return response()->json($student);
    }

    public function create(Request $request) {

        // Get the current admin ID
        $adminId = Auth::id();

        // Add 'admin_id' and set it to the current admin ID
        $request->merge(['admin_id' => $adminId]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'section_id' => 'required',
            'admin_id' => 'required',
            'phone_number' => 'string',
            'parent_name' => 'string',
            'parent_phone_number' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $section = Section::find($request->section_id);
        // Check the existence of the section
        if (!$section) {
            return response()->json(['error' => 'Section not found']);
        }
        
        if ($adminId != $section->admin_id ) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $student = Student::create(array_merge(
            $validator->validated(),
        ));

        return response()->json([
            'message' => 'Student added successfully.',
            'student' => $student
        ], 201);
    }

    public function all_students(){
        // Get the current admin ID
        $adminId = Auth::id();

        $admin = Admin::find($adminId);

        $students = $admin->students;
        return response()->json($students);
    }

    public function delete_student($id){
        $studentId = $id;
        // Get the current admin ID
        $adminId = Auth::id();

        // Get the admin model
        $admin = Admin::find($adminId);

        // Get the model of the specified student
        $student = Student::find($studentId);

        // Check the existence of the student
        if (!$student) {
            return response()->json([
                'error' => 'Student not found'
            ], 404);
        }

        // Check if the student belongs to the admin
        if ($student->admin_id != $admin->id) {
            return response()->json([
                'message' => 'Unauthorized action'
            ], 403);
        }

        // Delete student
        $student->delete();

        return response()->json([
            'message' => 'Student deleted successfully'
        ], 201);
    }

    public function update_student($id, Request $request){
        $studentId = $id;
        // Get the current admin ID
        $adminId = Auth::id();

        // Find the admin
        $admin = Admin::find($adminId);

        // Find the student
        $student = Student::find($studentId);

        // Check the existence of the student
        if (!$student) {
            return response()->json(['error' => 'Student not found']);
        }

        // Check if the student belongs to the admin
        if ($student->admin_id != $admin->id) {
            return response()->json(['message' => 'Unauthorized action']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'section_id' => 'required',
            'phone_number' => 'string',
            'parent_name' => 'string',
            'parent_phone_number' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        // Update the student with the new data
        $student->update($validator->validated());

        return response()->json([
            'message' => 'Student updated successfully',
            'student' => $student
        ]);
    }

    public function attendance($id,Request $request){
        $sectionId = $id;
        // Get the current admin ID
        $adminId = Auth::id();
    
        // Find the admin
        $admin = Admin::find($adminId);
    
        // Find the section
        $section = Section::find($sectionId);
    
        // Check the existence of the section
        if (!$section) {
            return response()->json(['error' => 'Section not found']);
        }
    
        // Check if the section belongs to the admin
        if ($section->admin_id != $admin->id) {
            return response()->json(['message' => 'Unauthorized action']);
        }

        $validator = Validator::make($request->all(), [
            'date' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $students = Student::all();
        $students = $section->students;
        $newValue = "0";
        foreach ($students as $student) {
            $oldValue = $student->attendance;
            list($beforeMark, $afterMark) = explode('/', $oldValue);

            if (in_array($student->id , $request->arr)) {

                $newValue = ($beforeMark) . '/' . ($afterMark+1);

                if ($student->date_of_absence == null) {
                    $date_of_absence = $request->date;
                }
                else {
                    $date_of_absence = $student->date_of_absence ." - ". $request->date;
                }

            } else {
                $newValue = ($beforeMark + 1) . '/' . ($afterMark+1);
                $date_of_absence = $student->date_of_absence;
            }
            
            
            $student->update([
                'attendance' => $newValue,
                'date_of_absence' => $date_of_absence
            ]);
        }

        return response()->json(['message' => 'successfully']);


    }


    public function studentProfile() {
        return response()->json(auth()->user());
    }
}