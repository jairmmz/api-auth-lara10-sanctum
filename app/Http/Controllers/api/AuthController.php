<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validación de datos
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
            ]);

            // Si hay errores de datos
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error de validación',
                    'data' => $validator->errors()
                ], 400);
            }

            // Creación de usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // Creación de token
            $token = $user->createToken('authToken')->plainTextToken;

            // Respuesta en JSON
            return response()->json([
                'status' => 'success',
                'message' => 'Usuario registrado correctamente',
                'data' => $user,
                'token' => $token
            ], 201);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function login(Request $request)
    {
        try {
            // Condición para validar si el usuario existe
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Credenciales incorrectas'
                ], 401);
            }

            // Obtener usuario
            $user = User::where('email', $request['email'])->firstOrFail();

            // Creación de token
            $token = $user->createToken('authToken')->plainTextToken;

            // Respuesta en JSON
            return response()->json([
                'status' => 'success',
                'message' => 'Usuario logueado correctamente',
                'data' => $user,
                'token' => $token
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function logout()
    {
        try {
            // Eliminar token
            auth()->user()->tokens()->delete();
            // $request->user()->tokens()->delete();

            // Respuesta en JSON
            return response()->json([
                'status' => 'success',
                'message' => 'Usuario deslogueado correctamente'
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function userProfile(Request $request)
    {
        try {
            // Obtener perfil del usuario
            return response()->json([
                'status' => 'success',
                'message' => 'Perfil de usuario',
                'data' => $request->user()
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function allUsers()
    {
        try {
            return response()->json([
                'status' => 'success',
                'message' => 'Listado de usuarios',
                'data' => UserResource::collection(User::all()),
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}