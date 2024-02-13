<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;



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
        $this->middleware('jwt.verify', ['except' => ['login', 'register','index']]);
    }


    public function index(){
        $student=Student::Get();
        return response()->json($student);

    }


   
    public function login(Request $request){
        

    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token); 
    }


   
    public function register(Request $request) {


        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|max:100|unique:students',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }



        $student = Student::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        
        return response()->json([
            'message' => 'student successfully registered',
            'student' => $student
        ], 201);
    }

    
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'student successfully signed out']);
    }
    

    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    
    
    public function studentProfile() {
        return response()->json(auth()->user());
    }
    
    
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
