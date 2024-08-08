<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Settings\GeneralSetting;

class SystemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function email()
    {
        $data = Collect([
            'mail_host' => env('MAIL_HOST'),
            'mail_port' => env('MAIL_PORT'),
            'mail_username' => env('MAIL_USERNAME'),
            'mail_password' => env('MAIL_PASSWORD'),
            'mail_encryption' => env('MAIL_ENCRYPTION'),
            'mail_from_address' => env('MAIL_FROM_ADDRESS'),
            'mail_from_name' => env('MAIL_FROM_NAME')
        ]);

        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function emailUpdate(Request $request)
    {
        $rules = [
            // 'mail_host' => 'required',
            // 'mail_port' => 'required',
            // 'mail_username' => 'required',
            // 'mail_password' => 'required',
            // 'mail_encryption' => 'required',
            // 'mail_from_address' => 'required',
            // 'mail_from_name' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'result' => $validator->errors(),
            ], 422);
        }else{
            DB::beginTransaction();
            try{
                    putenv('MAIL_HOST=asasa');
                    putenv('MAIL_PORT'. $request->mail_port);
                    putenv('MAIL_USERNAME'. $request->mail_username);
                    putenv('MAIL_PASSWORD'. $request->mail_password);
                    putenv('MAIL_ENCRYPTION'. $request->mail_encryption);
                    putenv('MAIL_FROM_ADDRESS'. $request->mail_from_address);
                    putenv('MAIL_FROM_NAME'. $request->mail_from_name);
                    
                    // $this->setEnvironmentValue('APP_LOG_LEVEL', 'app.log_level', 'debug');


            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'result' => $e,
                ], 422);
            }
            DB::commit();
            return response()->json([
                'success' => true,
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function emailTest(Request $request)
    {
        //
    }

    
    public function general()
    {
        // $data = Collect([
        //     'mail_host' => env('MAIL_HOST'),
        //     'mail_port' => env('MAIL_PORT'),
        //     'mail_username' => env('MAIL_USERNAME'),
        //     'mail_password' => env('MAIL_PASSWORD'),
        //     'mail_encryption' => env('MAIL_ENCRYPTION'),
        //     'mail_from_address' => env('MAIL_FROM_ADDRESS'),
        //     'mail_from_name' => env('MAIL_FROM_NAME')
        // ]);
        $data = resolve(GeneralSetting::class)->toArray();

        return response()->json($data, 200);
    }

    private function setEnvironmentValue($environmentName, $configKey, $newValue)
    {
        file_put_contents(App::environmentFilePath(), str_replace(
            $environmentName . '=' . Config::get($configKey),
            $environmentName . '=' . $newValue,
            file_get_contents(App::environmentFilePath())
        ));
    
        Config::set($configKey, $newValue);
    
        // Reload the cached config       
        if (file_exists(App::getCachedConfigPath())) {
            Artisan::call("config:cache");
        }
    }
}
