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

        // Get the current admin ID
        $adminId = Auth::id();

        // Add 'admin_id' and set it to the current admin ID
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
        // Get the current admin ID
        $adminId = Auth::id();

        $admin = Admin::find($adminId);

        $sections = $admin->sections;
        return response()->json($sections);
    }

    public function delete_section($id){
        $sectionId = $id;
        // Get the current admin ID
        $adminId = Auth::id();
    
        // Get the admin model
        $admin = Admin::find($adminId);
    
        // Get the model of the specified department
        $section = Section::find($sectionId);
    
        // Check the existence of the department
        if (!$section) {
            return response()->json([
                'error' => 'Section not found'
            ], 404);
        }
    
        // Check if the section belongs to the admin
        if ($section->admin_id != $admin->id) {
            return response()->json([
                'message' => 'Unauthorized action'
            ], 403);
        }
    
        // Delete section
        $section->delete();
    
        return response()->json([
            'message' => 'Section deleted successfully'
        ], 201);
    }
    

    public function update_section($id, Request $request){
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
            'days' => 'required|string',
            'time_period' => 'required|string',
            'color' => 'required|string',
            'notes' => 'required|string',
            'group' => 'required|string',
        ]);
    
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }    
    
        // Update the section with the new data
        $section->update($validator->validated());
    
        return response()->json([
            'message' => 'Section updated successfully',
            'section' => $section
        ]);
    }
    
}
