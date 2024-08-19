<?php

namespace App\Http\Controllers\Sales;

use App\Models\User;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;
use Hash;
use Carbon\Carbon;
use Browser;
use App\Models\Sales;

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

        $rules = [
            'username' => 'required|exists:sales,username',
            'password' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'result' => $validator->errors(),
            ], 422);

        }else{
            $user = Sales::with('branch')->where('username', $request->username)->first();

            if ($user != null) {
                if (Hash::check($request->password, $user->password)) {
                    $data = [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'phone' => $user->phone,
                        'branch' => $user->branch,
                        'token' => $user->createToken('auth-sales')->plainTextToken,
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
                        'message' => 'Login berhasil!',
                        'result' => $data
                    ], 200);
    
                } else {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Password Salah!', 
                        'result' => null
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
        auth('salesman')->user()->tokens()->delete();
        return [
            'success' => true,
            'message' => 'Akun Berhasil Keluar!',
        ];
    }
}
