<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    //
    public function __construct() {
        $this->middleware('jwt.verify', ['except' => ['index']]);
    }
    public function index(){
        $media=Media::Get();
        return $media;
    }

    public function create(Request $request , $id) {
        $request->merge(['course_id' => $id]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:courses',
            'link' => 'required|file|mimes:mp4,mov,avi,flv|max:20480',
            'course_id' =>'required',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $path = $request->file('link')->store('videos'); // Store file in 'videos' directory
        $validatedData = $validator->validated();
        $validatedData['link'] = $path; // Update 'link' field with file path

        $media = Media::create($validatedData); // Create media with validated data

        return response()->json([
            'message' => 'Admin successfully create media',
            'media' => $media
        ], 201);
    }
}
