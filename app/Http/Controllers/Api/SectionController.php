<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{
    //
    public function __construct() {
        $this->middleware('jwt.verify', ['except' => ['index']]);
    }

    public function index(){
        $section=Section::Get();
        return response()->json($section);
    }

    public function create(Request $request) {

        // الحصول على ID الadmin الحالي
        $adminId = Auth::id();

        // إضافة 'admin_id' وتعيينها إلى ID الadmin الحالي
        $request->merge(['admin_id' => $adminId]);

        $validator = Validator::make($request->all(), [
            'days' => 'required|string',
            'time_period' => 'required|string',
            'color' => 'required|string',
            'notes' => 'required|string',
            'group' => 'required|string',
            'admin_id' =>'required',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        
        $section = Section::create($validator->validated());
    
        return response()->json([
            'message' => 'Admin successfully create section',
            'section' => $section
        ], 201);
    }

    public function all_section(){
        // الحصول على ID الadmin الحالي
        $adminId = Auth::id();

        $admin = Admin::find($adminId);

        $sections = $admin->sections;
        return response()->json($sections);
    }

    public function delete_section($id){
        $sectionId = $id;
        // الحصول على ID الadmin الحالي
        $adminId = Auth::id();

        // الحصول على موديل المدير
        $admin = Admin::find($adminId);

        // الحصول على موديل القسم المعين
        $section = Section::find($sectionId);

        // التحقق من وجود القسم
        if (!$section) {
            return response()->json([
                'error' => 'Section not found'
            ], 404);
        }

        // التحقق مما إذا كان القسم ينتمي إلى المدير
        if ($section->admin_id != $admin->id) {
            return response()->json([
                'message' => 'Unauthorized action'
            ], 403);
        }

        // حذف section
        $section->delete();

        return response()->json([
            'message' => 'Section deleted successfully'
        ], 201);
    }

    public function update_section($id , Request $request){
        $sectionId = $id;
        // الحصول على ID الadmin الحالي
        $adminId = Auth::id();

        // البحث عن المدير
        $admin = Admin::find($adminId);

        // البحث عن القسم
        $section = Section::find($sectionId);

        // التحقق من وجود القسم
        if (!$section) {
            return response()->json(['error' => 'Section not found']);
        }

        // التحقق مما إذا كان القسم ينتمي إلى المدير
        if ($section->admin_id != $admin->id) {
            return response()->json(['message' => 'Unauthorized action']);
        }


        $validator = Validator::make($request->all(), [
            'days' => 'required|string',
            'time_period' => 'required|string',
            'color' => 'required|string',
            'notes' => 'required|string',
            'group' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }    

        // تحديث القسم باستخدام البيانات الجديدة
        $section->update($validator->validated());

        return response()->json([
            'message' => 'Section updated successfully',
            'section' => $section
        ]);
    }
}
