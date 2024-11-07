<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\Sales;

class SalesController extends Controller
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

        $user = auth()->user();
        
        $query = Sales::with(['branch'])
        ->when($request->name, function($q, $search) {
            $q->where('name', $search);
        })
        ->when($request->branch_id, function($q, $branch){
            $q->where('branch_id', $branch);
        })
        ->orderBy($sort, $sortDir);

        if($request->limit){
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
        $user = auth()->user();

        $rules = [
            'name' => 'required',
            'phone' => 'required|unique:sales,phone',
        ];
        if(empty($user->branch_id)){
            $rules['branch_id'] =  'required';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'result' => $validator->errors(),
            ], 422);

        }else{
            try
            {
                DB::beginTransaction();
                $data = new Sales();
                $data->name = $request->name;
                $data->email = $request->email;
                $data->phone = $request->phone;
                $data->username = $request->username;
                $data->password = $request->password;

                if(!empty($user->branch_id)){
                    $data->branch_id = $user->branch_id;
                }else{
                    $data->branch_id = $request->branch_id;
                }

                $data->save();

                DB::commit();
                return response()->json([
                    'success' => true,
                ], 200);
            }catch(\Exception $e){
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'result' => $e,
                ], 422);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        $data = Sales::with('branch')->where('id', $id)->first();

        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = auth()->user();

        $rules = [
            'name' => 'required',
            'phone' => 'required|unique:sales,phone,'.$id,
        ];

        if(empty($user->branch_id)){
            $rules['branch_id'] =  'required';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'result' => $validator->errors(),
            ], 422);

        }else{
            DB::beginTransaction();
            try{

                $user = auth()->user();

                $data = Sales::where('id', $id)->first();
                $data->name = $request->name;
                $data->email = $request->email;
                $data->phone = $request->phone;

                if(!empty($user->branch_id)){
                    $data->branch_id = $user->branch_id;
                }else{
                    $data->branch_id = $request->branch_id;
                }

                $data->branch_id = $request->branch_id;
                $data->save();

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
            $data = Sales::withCount('project')->where('id', $id)->first();
            if($data->project_count){
                $data->delete();
            }else{
                $data->forceDelete();
            }

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
