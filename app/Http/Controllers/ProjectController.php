<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\Project;
use App\Models\ProjectActivity;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProjectReport;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'DESC';
        $user = auth()->user();
        
        $query = Project::with(['branch', 'sales'])
        ->when(!empty($request->name), function($q, $search) {
            $q->where('name', 'LIKE', '%'.$search.'%');
        })
        ->when(!empty($request->branch_id), function($q, $branch_id) {
            $q->where('branch_id', $branch_id);
        })
        ->when(!empty($request->sales_id), function($q, $sales_id) {
            $q->where('sales_id', $sales_id);
        })
        ->orderBy($sort, $sortDir);

        if($request->limit){
            $data = $query->paginate($request->limit);
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
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'address' => 'required',
            'cp_name' => 'required',
            'cp_position' => 'required',
            'cp_phone' => 'required',
            'sales_id' => 'required',
            'amount' => 'required'
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
                $data = new Project();
                $data->name = $request->name;
                $data->address = $request->address;
                $data->amount = $request->amount;
                $data->customer_name = $request->customer_name;
                $data->customer_phone = $request->customer_phone;
                $data->customer_email = $request->customer_email;
                $data->cp_name = $request->cp_name;
                $data->cp_position = $request->cp_position;
                $data->cp_phone = $request->cp_phone;
                if(!empty($user->branch_id)){
                    $data->branch_id = $user->branch_id;
                }else{
                    $data->branch_id = $request->branch_id;
                }
                $data->sales_id = $request->sales_id;
                $data->is_ready = $request->is_ready;
                $data->status = $request->status;
                $data->link_map = $request->link_map;
                $data->save();

                DB::commit();
                return response()->json([
                    'success' => true,
                    'result' => $data,
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
        
        $data = Project::with(['branch', 'sales'])->where('id', $id)->first();

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
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'address' => 'required',
            'cp_name' => 'required',
            'cp_position' => 'required',
            'cp_phone' => 'required',
            'sales_id' => 'required',
            'amount' => 'required'
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
                $data = Project::where('id', $id)->first();
                $data->name = $request->name;
                $data->address = $request->address;
                $data->amount = $request->amount;
                $data->customer_name = $request->customer_name;
                $data->customer_phone = $request->customer_phone;
                $data->customer_email = $request->customer_email;
                $data->cp_name = $request->cp_name;
                $data->cp_position = $request->cp_position;
                $data->cp_phone = $request->cp_phone;
                if(!empty($user->branch_id)){
                    $data->branch_id = $user->branch_id;
                }else{
                    $data->branch_id = $request->branch_id;
                }
                $data->sales_id = $request->sales_id;
                $data->is_ready = $request->is_ready;
                $data->status = $request->status;
                $data->link_map = $request->link_map;
                $data->save();

                DB::commit();
                return response()->json([
                    'success' => true,
                    'result' => $data,
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try{
            $data = Project::where('id', $id)->first();
            $data->delete();

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
    
    public function activity(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;
        $page = $request->page;
        
        $query = ProjectActivity::when(!empty($search), function($q, $search) {
            $q->where('name', $search);
        })
        ->orderBy($sort, $sortDir);

        if($page){
            $data = $query->paginate($limit);
        }else{
            $data = $query->get();
        }

        return response()->json($data);
    }

    public function export(Request $request)
    {
        $search = $request->nama ?? '';
        $branch_id = $request->branch_id;
        $sales_id = $request->sales_id;
        $status = $request->status;

        return Excel::download(new ProjectReport($search, $branch_id, $sales_id, $status), 'Report Project.xlsx');
    }


    public function done(Request $request, string $id)
    {
        $user = auth()->user();
        // dd($request->note);
        try
        {
            DB::beginTransaction();
            $data = Project::where('id', $id)->first();
            $data->note = $request->note;
            $data->status = 'done';
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
