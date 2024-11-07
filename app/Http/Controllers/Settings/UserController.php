<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;
        $page = $request->page;
        
        $query = User::with(['branch'])
        ->when(!empty($search), function($q, $search) {
            $q->where('kota_id', $kota_id);
        })
        ->orderBy($sort, $sortDir);

        if($page){
            $data = $query->paginate($limit);
        }else{
            $data = $query->get();
        }

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'username' => 'required|unique:users,username',
            'role' => 'required',
            'password' => 'required|string',
            'password_confirmation' => 'required'
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

                $data = new User();
                $data->name = $request->name;
                $data->email = $request->email;
                $data->username = $request->username;
                $data->branch_id = $request->branch_id;
                $data->password = $request->password;
                $data->save();

                $data->assignRole($request->role);
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        $data = User::with('roles')->where('id', $id)->first();

        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$id,
            'username' => 'required|unique:users,username,'.$id,
            'role' => 'required',
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

                $data = User::where('id', $id)->first();
                $data->name = $request->name;
                $data->email = $request->email;
                $data->username = $request->username;
                $data->branch_id = $request->branch_id;
                if(!empty($request->password)){
                    $data->password = $request->password;
                }
                $data->save();

                $data->syncRoles([$request->role]);
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try{

            $data = User::where('id', $id)->first();
            $data->delete();

            $data->assignRole($request->role);
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
