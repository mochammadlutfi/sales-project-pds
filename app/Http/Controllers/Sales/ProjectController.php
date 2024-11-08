<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\Project;

class ProjectController extends Controller
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
        
        $query = Project::with(['branch'])
        ->when(!empty($search), function($q, $search) {
            $q->where('name', $search);
        })
        ->where('sales_id', $user->id)
        ->orderBy($sort, $sortDir);

        if($page){
            $data = $query->paginate($limit);
        }else{
            $data = $query->get();
        }

        // $data = ['sadas'];

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'address' => 'required',
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'customer_email' => 'required',
            'cp_name' => 'required',
            'cp_position' => 'required',
            'cp_phone' => 'required',
            'amount' => 'required|string'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'result' => $validator->errors(),
            ]);

        }else{
            try
            {
                DB::beginTransaction();
				$auth = auth()->guard('salesman')->user();
				
                $data = new Project();
                $data->name = $request->name;
                $data->address = $request->address;
                $data->customer_name = $request->customer_name;
                $data->customer_phone = $request->customer_phone;
                $data->customer_email = $request->customer_email;
                $data->cp_name = $request->cp_name;
                $data->cp_position = $request->cp_position;
                $data->cp_phone = $request->cp_phone;
                $data->branch_id = $auth->branch_id;
                $data->sales_id = $auth->id;
                $data->is_ready = $request->is_ready;
                $data->amount = $request->amount;
                $data->status = 'Draft';
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
        
        $data = Project::with('branch')->where('id', $id)->first();

        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        {
            $rules = [
                'name' => 'required',
                'phone' => 'required|unique:sales,phone,'.$id,
                'username' => 'required|unique:sales,username,'.$id,
                'branch_id' => 'required',
                // 'password' => 'required|string',
                // 'password_confirmation' => 'required'
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

                    $data = Sales::where('id', $id)->first();
                    $data->name = $request->name;
                    $data->phone = $request->phone;
                    $data->branch_id = $request->branch_id;
                    $data->username = $request->username;
                    // $data->password = $request->password;
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
    }
}
