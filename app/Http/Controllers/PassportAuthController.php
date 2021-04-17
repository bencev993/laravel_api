<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Route;
use App\Models\User;

class PassportAuthController extends Controller
{
    use AuthenticatesUsers;

    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('AccessToken', ['customer'])->accessToken;

        return response()->json(['token' => $token], 200);
    }


    public function login(Request $request)
    {

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            if(auth()->user()->is_admin) {
                $token = auth()->user()->createToken('AccessToken', ['admin']);
            } else {
                $token = auth()->user()->createToken('AccessToken', ['customer']);
            }

            $user = auth()->user();

            return response()->json([
                'token' => $token->accessToken,
                'scope' => $token->token->scopes[0],
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'city' => $user->city,
                'country' => $user->country,
                'zip' => $user->zip,
            ], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }


}
