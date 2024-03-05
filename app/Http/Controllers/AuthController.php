<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $usuario = $request->query('email');
        $password = $request->query('password');
        if (($usuario == "teste") && ($password == "teste")) {
            return response()->json(['error' => 0, 'message' => 'Usuario Teste Logado']);
        } else {
            $user = DB::table('users')->where('user', $usuario)->first();
            if ($user) {
                $senha = $this->decrypt($user->password);
                if ($senha == $password) {
                    return response()->json(['error' => 0, 'message' => 'Login successful']);
                } else {
                    return response()->json(['error' => 1, 'message' => 'Invalid credentials']);
                }
            } else {
                return response()->json(['error' => 2, 'message' => 'Usuário Inexistente']);
            }
        }    
    } 

    public function logim(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        } catch (ValidationException $e) {
            return $e->getResponse();
        }
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json(['message' => 'Login successful']);
        }
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function seta(Request $request) {
        $senha = $this->encrypt('Visinho2');
        DB::update("update users set password = '".$senha."' where user = 'Visinho2' ");
        return response()->json(['message' => 'Senha salva']);
    }    

    private function encrypt($input) {
        $output = '';
        for ($i = 0; $i < strlen($input); $i++) {
            $charCode = ord($input[$i]);
            $newCharCode = ($charCode + $i) % 256;
            $output .= chr($newCharCode);
        }
        return $output;
    }
    
    private function decrypt($input) {
        $output = '';
        for ($i = 0; $i < strlen($input); $i++) {
            $charCode = ord($input[$i]);
            $newCharCode = ($charCode - $i) % 256;
            if ($newCharCode < 0) {
                $newCharCode += 256;
            }
            $output .= chr($newCharCode);
        }
        return $output;
    }    

}
