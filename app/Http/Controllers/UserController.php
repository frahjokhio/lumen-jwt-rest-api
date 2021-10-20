<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register'] ]);
    }

    public function register(Request $request){

        // validate data
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);
        // end validation

        $input = $request->only('name', 'email', 'password');
        
        // register user
        try {

            $user = new User; // create new user instance
            $user->name = $input['name'];
            $user->email = $input['email'];
            $password = $input['password'];

            $user->password = app('hash')->make($password);
            // this will create hash string 

            // save user
            if( $user->save() ){

                $code = 200;
                $output = [
                    'user' => $user,
                    'code' => $code,
                    'message'  => 'User created successfully.'
                ];

            } else {
                $code = 500;
                $output = [
                    'code' => $code,
                    'message'  => 'An error occured while creating user.'
                ];
            }

        } catch (Exception $e) {
            //dd($e->getMessage());
            $code = 500;
            $output = [
                'code' => $code,
                'message'  => 'An error occured while creating user.'
            ];
        }

        // end register user

        // return response

        return response()->json($output, $code);

    }


    public function login(Request $request){


        // validate data
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        // end validation

        $input = $request->only('email', 'password');

        if( ! $authorized = Auth::attempt($input) ){

            $code = 401;
            $output = [
                'code' => $code,
                'message'  => 'User is not authorized'
            ];

        } else {
            
            $code = 201;
            $token = $this->respondWithToken($authorized);
            $output = [
                'code' => $code,
                'message'  => 'User logged in successfully.',
                'token' => $token
            ];
        }

        return response()->json($output, $code);
    }

    public function me(){

        return response()->json( $this->guard()->user() );
    }

    public function refresh(){

        return $this->respondWithToken( $this->guard()->refresh() );
    }

    public function logout(){

        $this->guard()->logout();
        return response()->json(['message' => 'Logged Out!']);
    }

    public function guard(){

        return Auth::guard();
    }
}
