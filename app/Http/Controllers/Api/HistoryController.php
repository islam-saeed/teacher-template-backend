<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\History;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    //
    public function __construct() {
        $this->middleware('jwt.verify');
    }


    public function show()
    {
        // Get the current admin ID
        $adminId = Auth::id();

        $admin = Admin::find($adminId);

        $history = $admin->history;
        return response()->json($history);

        
    }

    public function delete($id)
    {
        // Get the current admin ID
        $adminId = Auth::id();
        $admin = Admin::find($adminId);

        $history = History::find($id);
            
        
        







        if ($history->action == "create section") {
            $sectionId = $history->model_id;
            $section = Section::find($sectionId);
            $section->delete();

            $history = $admin->history;

        }elseif ($history->action == "create student") {
            $studentId = $history->model_id;
            $student = Student::find($studentId);
            $student->delete();

            $history = $admin->history;

        }elseif ($history->action == "update section") {

            
            $data = json_decode($history->data, true);

            $section = History::where('model_id',$history->model_id)
            ->where('updated_at', '<', $data['updated_at'])
            ->orderBy('updated_at', 'desc')
            ->first();
            $data_of_section = json_decode($section->data, true);

            $requestData = new Request($data_of_section);
            $sectionController = new SectionController();
            $sectionController->update_section($section->model_id, $requestData);

        }elseif ($history->action == "update student") {

            
            $data = json_decode($history->data, true);

            $student = History::where('model_id',$history->model_id)
            ->where('updated_at', '<', $data['updated_at'])
            ->orderBy('updated_at', 'desc')
            ->first();
            $data_of_student = json_decode($student->data, true);

            $requestData = new Request($data_of_student);
            $studentController = new StudentController();
            $studentController->update_student($student->model_id, $requestData);

        }elseif ($history->action == "delete section") {
            $data = json_decode($history->data, true);


            $requestData = new Request($data);
            $sectionController = new SectionController();
            $sectionController->create($requestData);

            

        }elseif ($history->action == "delete student") {
            $data = json_decode($history->data, true);


            $requestData = new Request($data);
            $studentController = new StudentController();
            $studentController->create($requestData);

        }

        return response()->json($history);





       

        

        
    }
}
