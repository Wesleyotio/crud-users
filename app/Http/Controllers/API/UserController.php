<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function list() {
        try {
            //code...
            if (Auth::check()) {

                $users = User::whereNotIn('id', [Auth::user()->id])->get()->toArray();
                $response = [
                    'success' => true,
                    'users' => $users,
                ];
                return response()->json($response, 200);
            }
            $response = [
                'success' => false,
                'message' => 'Usuário não autenticado',
            ];
            return response()->json($response, 401);
        } catch (\Throwable $th) {
            $response = [
                'success' => false,
                'message' => $th->getMessage()
            ];
            return response()->json($response, 500);
        }
    }
    public function show($id) {
        try {
            //code...
            if (Auth::check()) {

                $user = User::find($id);
                $response = [
                    'success' => true,
                    'users' => $user,
                ];
                return response()->json($response, 200);
            }
            $response = [
                'success' => false,
                'message' => 'Usuário não autenticado',
            ];
            return response()->json($response, 401);
        } catch (\Throwable $th) {
            $response = [
                'success' => false,
                'message' => $th->getMessage()
            ];
            return response()->json($response, 500);
        }
    }

    public function register(Request $request) {
        // validate the request data

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|digits_between:11,11'
        ]);

        try {
            //code...
            // create the new user in the database
            $user = User::create([
                'status' => 'active',
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone ?? null,
            ]);

            // log the user in
            Auth::login($user, true);
            $message = 'Usuário registrado com sucesso';
            $response = [
                'user'      => Auth::user()->name,
                'userId'    => Auth::user()->id,
                'token'     => $user->createToken("API TOKEN")->plainTextToken,
                'success'   => true,
                'message'   => 'Usuário registrado e logado com sucesso',
            ];
            return response()->json($response, 200);
        } catch (QueryException $error) {
            $success = false;
            $message = $error;

            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response, 500);
        }

    }

    public function createUser(Request $request ) {
        // validate the request data

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|digits_between:11,11'
        ]);

        try {
            //code...
            // create the new user in the database
            if(Auth::check()) {

                $user = User::create([
                    'status' => 'active',
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'phone' => $request->phone ?? null,
                ]);

                $response = [
                    'success'   => true,
                    'message'   => 'Usuário registrado com sucesso',
                ];
                return response()->json($response, 200);
            }
            
            $response = [
                'success' => false,
                'message' => 'Usuário não autenticado!'
            ];
            return response()->json($response, 401);
        } catch (QueryException $error) {
            $success = false;
            $message = $error;

            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response, 500);
        }

    }

    public function login(Request $request)
    {
        //validate the request data
        try {
            //code...
            $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required|string',
            ]);

            //attempt to authenticate the user
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials, true)) {
                $user = User::where('email', $credentials['email'])->first();
                Auth::login($user, true);
                $response = [
                    'user'      => Auth::user()->name,
                    'userId'    => $user->id,
                    'token'     => $user->createToken("API TOKEN")->plainTextToken,
                    'success'   => true,
                    'message'   => "Usuário logado com sucesso"
                ];
                return response()->json($response, 200);
            } else {
                //if the user's credentials are invalid, redirect back to the login page with an error message
                $response = [
                    'success' => false,
                    'message' => 'email ou senha inválidos'
                ];
                return response()->json($response, 401);
            }
        } catch (\Throwable $th) {
            $response = [
                'success' => false,
                'message' => $th->getMessage()
            ];
            return response()->json($response, 500);
        }

    }


    public function update(Request $request) {
        try {
            //code...
            if(Auth::user()) {

                $user = Auth::user();
                $request->validate([
                    'name' => 'sometimes|string|max:255',
                    'email' => 'sometimes|string|email|max:255|unique:users,email,'.$user->id,
                    'password' => 'sometimes|string|min:8|confirmed',
                    'phone' => 'nullable|string|digits_between:11,11',
                ]);


                $user->fill($request->only([
                    'email'
                ]));

                if ($request->filled('password')) {
                    $user->password = bcrypt($request->password);
                }
                $user->save();
                $id = $user->id;

                if ($request->hasFile('userImage')) {
                    $image = $request->file('userImage');
                    if ($image->isValid()) {

                        $file_name = $image->getFilename();
                        $image->store('images', 'storage');
                        DB::transaction(function () use( $id , $file_name){
                            DB::insert('insert into user_images (user_id, user_name_image) values (?, ?)', [$id, $file_name]);

                        }, 5);
                    }
                }
                Session::flush();

                $response = [
                    'success' => true,
                    'message' => "Usuário atualizado com sucesso",
                ];
                return response()->json($response, 200);
            }

            $response = [
                'success' => false,
                'message' => "Usuário não autenticado",
            ];
            return response()->json($response, 401);

        } catch (\Throwable $th) {
            //throw $th;
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response, 500);
        }


    }

    public function logout() {
        try {
            //code...

            if(Auth::check()){
                Auth::user()->tokens()->delete();
                $response = [
                    'success' => true,
                    'message' => 'Usuário deslogado com sucesso',
                ];
                return response()->json($response, 200);
            }
            $response = [
                'success' => false,
                'message' => "Usuário não autenticado",
            ];
            return response()->json($response, 401);



        } catch (\Throwable $th) {
            //throw $th;
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response, 500);
        }


    }

    public function delete($id) {
        try {
            //code...

            if(Auth::user()) {

                $user = User::find($id);
                $user->delete();
                $response = [
                    'success' => true,
                    'message' => 'Usuário removido com sucesso',
                ];
                return response()->json($response, 200);
            }
            $response = [
                'success' => false,
                'message' => "Usuário não autenticado",
            ];
            return response()->json($response, 401);

        } catch (\Throwable $th) {
            //throw $th;
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }

    public function imageUser(Request $request) {
        try {
            //code...

            if(Auth::check()) {

                $id = Auth::user()->id;
                if ($id && $request->hasFile('userImage')) {

                    $image = $request->file('userImage');
                    if ($image->isValid()) {

                        $file_name = $image->getClientOriginalName();
                        $path = Storage::disk('local')->putFileAs('images', $image, $file_name);

                        DB::transaction(function () use( $id , $file_name){
                            $result = DB::select('select id from user_images where user_id = :id', ['id' => $id]);

                            if(!$result) {
                                DB::insert('insert into user_images (user_id, user_name_image) values (?, ?)', [$id, $file_name]);
                                return;
                            }
                            DB::update(
                                'update user_images
                                    set user_name_image = ?
                                    where user_id = ?',[$file_name, $id]);


                        }, 5);
                        $response = [
                            'success' => true,
                            'imagemPath' =>  $path,
                            'message' => 'Imagem enviada com sucesso!'
                        ];
                        return response()->json($response, 200);
                    }
                    $response = [
                        'success' => false,
                        'message' => "O arquivo enviado não é valido!",
                    ];
                    return response()->json($response, 400);
                }
                $response = [
                    'success' => false,
                    'message' => "Arquivo não encontrado!",
                ];
                return response()->json($response, 400);
            }
            $response = [
                'success' => false,
                'message' => "Usuário não autenticado",
            ];
            return response()->json($response, 401);

        } catch (\Throwable $th) {
            //throw $th;
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }
}

