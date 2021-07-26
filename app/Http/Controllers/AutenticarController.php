<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\CreateRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;



class AutenticarController extends Controller
{
    public function create(CreateRequest $request){

            // $rule = [
            //     'name' => 'required',
            //     'email' => 'required|email|unique:users,email',
            //     'password' => 'required',
            // ];

            // $message = [
            //    'required' => 'El campo :attribute es requerido',
            //    'unique' => 'Este valor ya se encuentra registrado'
            // ];

            // $validator = Validator::make($request->all(), $rule, $message);

            // if($validator->fails()){
            //     return response()->json([
            //         'status' => 'fail',
            //         'message' => 'Estos campos son requeridos',
            //         'result' => $validator->errors()
            //     ], 400);
            // }

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => "Usuario Registrado Correctamente",
                'result' => $user,

            ], 201);




    }

    public function login(LoginRequest $request){

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['Las credenciales suministrada no son correctas.'],
            ]);
        }

        $token =  $user->createToken($request->email)->plainTextToken;



        return response()->json([
            'status' => 'success',
            'message' => "Usuario autenticado",
            'result' => $token,

        ], 201);
    }

    public function logout(Request $request){
       // Revoke the token that was used to authenticate the current request...
        $request->user()->currentAccessToken()->delete();


        return response()->json([
            'status' => 'success',
            'message' => "Token Eliminado",
        ], 200);
    }


    public function comprobacion(){
        return response()->json([
            "message" => "Lograste Llegar"
        ]);
    }
}
