<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    //
    public function showHistory($sectiontId)
    {
        $history = History::where('model_id', $sectiontId)->get();


        return response()->json([
            'history' => $history
        ]);
    }
}
