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
    
    public function groups_of_admin(){
        // Get the current admin ID
        $adminId = Auth::id();

        $admin = Admin::find($adminId);
        
        $uniqueGroups = $admin->sections()->distinct()->pluck('group');

        return response()->json($uniqueGroups);
    }

    public function fees($id, Request $request){

        // $jsonString1 = '{"2": {"100": ["3", "4"]}}';
        // $jsonString2 = '{"2": {"100": ["5", "6"]}}';

        // // تحويل JSON إلى مصفوفات PHP
        // $array1 = json_decode($jsonString1, true);
        // $array2 = json_decode($jsonString2, true);

        // // foreach ($array2 as $key => &$value) {
        // //     // return $value;
        // //     $array1[$key] = $value;
        // // }
        // // return $array1;

        // // دمج المصفوفات بشكل متكامل
        // $mergedArray = array_merge_recursive_distinct($array1, $array2);

        // // تحويل المصفوفة المدمجة إلى JSON
        // $mergedJsonString = json_encode($mergedArray);

        // return compact('array1', 'array2','mergedArray');


        // return $mergedArray;








        //////////////////////////////////////////////////////
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

        $array1 = json_decode($section->fees, true);
        $array2 = $request->fees;


        $validator = Validator::make($request->all(), [
            'fees' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        if ($array1==null) {
            $array1=$array2;
        }
        else {
            foreach ($array2 as $key => $values) {
                if (array_key_exists($key, $array1)) {    
                    foreach ($values as $subKey => $subValues) {
                        if (array_key_exists($subKey, $array1[$key])) {
                            return response()->json([
                                'message' => ' This student has already paid the fees ',
                                'id'=>$subKey,
                            ]);
                        } else {
                            $array1[$key][$subKey] = $subValues;
                        }
                    }
                } else {
                    $array1[$key] = $values;    
                }
            }
        }
        $section->fees = $array1;
        

        $section->save();    
        return response()->json([
            'message' => ' successfully ',
            'fees' => $section->fees
        ]);



    }

    
    // public function fees($id, Request $request){

    //     // $jsonString1 = '{"2": {"100": ["3", "4"]}}';
    //     // $jsonString2 = '{"2": {"100": ["5", "6"]}}';

    //     // // تحويل JSON إلى مصفوفات PHP
    //     // $array1 = json_decode($jsonString1, true);
    //     // $array2 = json_decode($jsonString2, true);

    //     // // foreach ($array2 as $key => &$value) {
    //     // //     // return $value;
    //     // //     $array1[$key] = $value;
    //     // // }
    //     // // return $array1;

    //     // // دمج المصفوفات بشكل متكامل
    //     // $mergedArray = array_merge_recursive_distinct($array1, $array2);

    //     // // تحويل المصفوفة المدمجة إلى JSON
    //     // $mergedJsonString = json_encode($mergedArray);

    //     // return compact('array1', 'array2','mergedArray');


    //     // return $mergedArray;








    //     //////////////////////////////////////////////////////
    //     $sectionId = $id;
    //     // Get the current admin ID
    //     $adminId = Auth::id();
    
    //     // Find the admin
    //     $admin = Admin::find($adminId);
    
    //     // Find the section
    //     $section = Section::find($sectionId);
    
    //     // Check the existence of the section
    //     if (!$section) {
    //         return response()->json(['error' => 'Section not found']);
    //     }
    
    //     // Check if the section belongs to the admin
    //     if ($section->admin_id != $admin->id) {
    //         return response()->json(['message' => 'Unauthorized action']);
    //     }

    //     $array1 = json_decode($section->fees, true);
    //     $array2 = $request->fees;


    //     $validator = Validator::make($request->all(), [
    //         'fees' => 'required',
    //     ]);

    //     if($validator->fails()){
    //         return response()->json($validator->errors()->toJson(), 400);
    //     }

    //     if ($array1==null) {
    //         $array1=$array2;
    //     }
    //     else {
    //         foreach ($array2 as $key => $values) {
    //             if (array_key_exists($key, $array1)) {    
    //                 foreach ($values as $subKey => $subValues) {
    //                     if (array_key_exists($subKey, $array1[$key])) {
    //                         $array1[$key][$subKey] = array_merge($array1[$key][$subKey], $subValues);
    //                     } else {
    //                         $array1[$key][$subKey] = $subValues;
    //                     }
    //                 }
    //             } else {
    //                 $array1[$key] = $values;    
    //             }
    //         }
    //     }
    //     $section->fees = $array1;
        

    //     $section->save();    
    //     return response()->json([
    //         'message' => ' successfully ',
    //         'fees' => $section->fees
    //     ]);



    // }
    


    
}
