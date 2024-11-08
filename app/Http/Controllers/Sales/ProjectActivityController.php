<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\ProjectActivity;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
class ProjectActivityController extends Controller
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
        $project_id = $request->project_id;
        
        $user = auth()->user();

        $query = ProjectActivity::
        when($project_id, function($q, $project_id) {
            return $q->where('project_id', $project_id);
        })
        ->when(empty($project_id), function($q) {
            return $q->with(['project']);
        })
        ->where('sales_id', $user->id)
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
        // dd($request->all());
        $rules = [
            'date' => 'required',
            'description' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'result' => $validator->errors(),
            ], 422);

        }else{
            DB::beginTransaction();
            try
            {
                $data = new ProjectActivity();
                $data->project_id = $request->project_id;
                $data->date = $request->date;
                $data->description = $request->description;
                // $data->image = $request->image;
                $data->save();

                if(!empty($request->file('image'))){
                    $data->image = $this->uploadImage($request->file('image'), $data->id);
                    $data->save();
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    } 

    
    private function uploadImage($file, $project_id){

        $file_name = uniqid() . '.' . $file->getClientOriginalExtension();

        $imgFile = ImageManager::gd()->read($file->getRealPath());

        $destinationPath = storage_path('app/public/project/'.$project_id);
        $return = '/uploads/project/'.$project_id.'/'.$file_name;

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 755, true);
        }

        $width = 1000;
        $heigth = 1000;

        $imgFile->resize($width, $heigth, function ($constraint) {
		    $constraint->aspectRatio();
		})->save($destinationPath.'/'.$file_name, 90);

        return $return;
    }
    
}
