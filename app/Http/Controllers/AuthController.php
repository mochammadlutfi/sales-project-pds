<?php

namespace App\Http\Controllers;

use App\Models\User;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;
use Hash;
use Carbon\Carbon;
use Browser;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | API Authentication Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating admin users for the application and
    | redirecting them to your admin dashboard.
    |
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Login User.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $rules = [
            'email' => 'required|exists:users,'.$fieldType,
            'password' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'result' => $validator->errors(),
            ], 422);

        }else{
            $user = User::where($fieldType, $request->email)->first();

            if ($user != null) {
                if (Hash::check($request->password, $user->password)) {
                    $data = [
                        'user' => $user,
                        'access_token' => $user->createToken('auth-token')->plainTextToken,
                        'token_type' => 'Bearer ',
                        'permissions' => $user->getPermissionsViaRoles()->pluck('name'),
                    ];

                    $browser = Browser::parse($request->userAgent());
                    $device = $browser->platformName() . ' / ' . $browser->browserName();
            
                    $sanctumToken = $user->createToken(
                        $device,
                        ['*'],
                        $request->remember ?
                            now()->addMonth() :
                            now()->addDay()
                    );
            
                    $sanctumToken->accessToken->ip = $request->ip();
                    $sanctumToken->accessToken->save();
                    return response()->json([
                        'success' => true,
                        'result' => $data
                    ], 200);
    
                } else {
                    return response()->json([
                        'success' => false,
                        'result' => ['password' => 'Password Salah!'] 
                    ], 401);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Tidak Ditemukan!'
                ], 401);
            }
        }
    }

    /**
     * Logout the admin.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        auth('sanctum')->user()->tokens()->delete();
        return [
            'success' => true,
            'message' => 'Akun Berhasil Keluar!',
        ];
    }
}
