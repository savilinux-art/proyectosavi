<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Login de usuario
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|string',
            'contraseña' => 'required|string',
            'device_name' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $usuario = Usuario::where('usuario', $request->usuario)->first();

        if (!$usuario || !Hash::check($request->contraseña, $usuario->contraseña)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        $deviceName = $request->device_name ?? 'mobile_app';
        $token = $usuario->createToken($deviceName)->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $usuario->id,
                'usuario' => $usuario->usuario,
                'nombre' => $usuario->nombre,
                'correo' => $usuario->correo,
                'rol' => $usuario->rol
            ]
        ]);
    }

    /**
     * Registrar nuevo usuario
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|string|max:255|unique:usuarios',
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|max:255|unique:usuarios',
            'contraseña' => 'required|string|min:6|confirmed',
            'rol' => 'nullable|string|exists:roles,rol'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Por defecto, asignar rol 'Usuario' o el que se especifique
        $rol = $request->rol ?? 'Ventas';
        
        // Verificar que el rol existe
        if (!Rol::where('rol', $rol)->exists()) {
            $rol = 'Ventas';
        }

        $usuario = Usuario::create([
            'usuario' => $request->usuario,
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'contraseña' => Hash::make($request->contraseña),
            'rol' => $rol
        ]);

        $token = $usuario->createToken('mobile_app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado exitosamente',
            'token' => $token,
            'user' => [
                'id' => $usuario->id,
                'usuario' => $usuario->usuario,
                'nombre' => $usuario->nombre,
                'correo' => $usuario->correo,
                'rol' => $usuario->rol
            ]
        ], 201);
    }

    /**
     * Logout de usuario
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }

    /**
     * Obtener usuario autenticado
     */
    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }

    /**
     * Refrescar token
     */
    public function refreshToken(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $newToken = $user->createToken('mobile_app')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $newToken
        ]);
    }
}