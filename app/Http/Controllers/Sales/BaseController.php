<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use App\Models\Sales;
use App\Models\Branch;
class BaseController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function sales(Request $request): JsonResponse
    {
        // $id = auth()->user()->id;

        $branch_id = $request->branch_id;

        $data = Sales::
        when($branch_id, function($query, $branch_id){
            $query->where('branch_id', $branch_id);
        })->orderBy('name', 'ASC')->get();
        
        return response()->json($data);
    }

    /**
     * Update the user's profile information.
     */
    public function branch(Request $request): JsonResponse
    {
        $data = Branch::orderBy('name', 'ASC')->get();
        
        return response()->json($data);
    }

}
