<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\History;
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
}
