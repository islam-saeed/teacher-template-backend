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
    //
    //
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('jwt.verify', ['except' => [ 'index']]);
    }


    public function index(){
        $student=Student::Get();
        return response()->json($student);

    }
   
    public function create(Request $request) {

        // الحصول على ID الadmin الحالي
        $adminId = Auth::id();

        // إضافة 'admin_id' وتعيينها إلى ID الadmin الحالي
        $request->merge(['admin_id' => $adminId]);


        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'section_id' => 'required',
            'admin_id' => 'required',
            'phone_number' => 'string',
            'parent_name' => 'string',
            'parent_phone_number' => 'string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $section = Section::find($request->section_id);

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
        // الحصول على ID الadmin الحالي
        $adminId = Auth::id();

        $admin = Admin::find($adminId);

        $students = $admin->students;
        return response()->json($students);
    }

    
   

   
    
    public function studentProfile() {
        return response()->json(auth()->user());
    }
    
}
