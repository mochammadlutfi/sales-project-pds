<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Settings\GeneralSetting;
use App\Models\Locale;
use Carbon\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class BaseController extends Controller
{

    public function index()
    {
        $data = resolve(GeneralSetting::class)->toArray();
        $data['locales'] = Locale::where('is_active', true)->get();

        return response()->json($data);
    }

    public function setLang($lang)
    {
        App::setLocale($lang);
    }

    function test(){
        
        // $list_permission = [
        //     ['name' => 'settings.system'],
        //     ['name' => 'settings.general'],
        //     ['name' => 'settings.email'],
        //     ['name' => 'settings.language'],

        //     ['name' => 'user.view'],
        //     ['name' => 'user.create'],
        //     ['name' => 'user.update'],
        //     ['name' => 'user.delete'],

        //     ['name' => 'permission.view'],
        //     ['name' => 'permission.create'],
        //     ['name' => 'permission.update'],
        //     ['name' => 'permission.delete'],

        //     ['name' => 'branch.view'],
        //     ['name' => 'branch.create'],
        //     ['name' => 'branch.update'],
        //     ['name' => 'branch.delete'],
            
        //     ['name' => 'sales.view'],
        //     ['name' => 'sales.create'],
        //     ['name' => 'sales.update'],
        //     ['name' => 'sales.delete'],

        //     ['name' => 'project.view'],
        //     ['name' => 'project.create'],
        //     ['name' => 'project.update'],
        //     ['name' => 'project.delete'],
            
        //     ['name' => 'project.activity.view'],
        //     ['name' => 'project.activity.create'],
        //     ['name' => 'project.activity.update'],
        //     ['name' => 'project.activity.delete'],
        // ];

        // $insert_data = [];
        // $time_stamp = Carbon::now()->toDateTimeString();
        // foreach ($list_permission as $d) {
        //     $d['guard_name'] = 'sanctum';
        //     $d['created_at'] = $time_stamp;
        //     $insert_data[] = $d;
        // }

        // Permission::insert($insert_data);
        // dd($permissions);

        $role = Role::where('name', 'Operator')->first();
        // $permissions = Permission::latest('id')->get()->pluck('id');
        
        $role->syncPermissions($permissions);
    }

}