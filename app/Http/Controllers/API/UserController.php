<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    //
    public function register(Request $request){
        try{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $success = true;
            $message = 'Usuário registrado com sucesso';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response, 200);
        } catch(QueryException $error){
            $success = false;
            $message = $error;

        }
        $response = [
            'success' => $success,
            'message' => $message,
        ];
        return response()->json($response, 500);

    }

    public function login(Request $request){


        $response = [];
        $dados = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::attempt($dados)){
            $response = [
                'user'    => Auth::user()->name,
                'userId'  => Auth::user()->id,
                'success' => true,
                'message' => "Usuario logado com sucesso"
            ];
            return response()->json($response, 200);
        }else{

            $success = false;
            $message = 'email ou login inválidos';
        }
        $response = [
            'success' => $success,
            'message' => $message
        ];
        return response()->json($response, 400);

    }

    public function logout(){

        $response = [];
        try{
            Session::flush();
            $success = true;
            $message = 'Usuario deslogado com sucesso';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response, 200);
        } catch(QueryException $error){
            $success = false;
            $message = $error->getMessage();
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response, 500);
        }

    }
    public function update(Request $request){

        try {
            $user = User::find(Auth::user()->id);
            $user->update($request->all());
            $this->logout();

            $response = [
                'success' => true,
                'message' => "Usuário atualizado com sucesso",
            ];
            return response()->json($response, 200);
        } catch (\Throwable $th) {

            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response, 500);
        }


    }
    public function list(){

        try {

            $users = User::all();
            return response()->json($users, 200);

        } catch (\Throwable $th) {

            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }
    public function show($id){

        try {

            $user = User::select(['name','email','phone','photo'])->find($id);
            return response()->json($user, 200);

        } catch (\Throwable $th) {

            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response, 500);
        }


    }
    public function remove($id){
        $user = User::find($id);

        try {
            $user->delete();
            $response = [
                'success' => true,
                'message' => "Usuário removido com sucesso",
            ];
            return response()->json($response, 200);

        } catch (\Throwable $th) {
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response, 500);
        }

    }
}
