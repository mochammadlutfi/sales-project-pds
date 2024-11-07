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

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = collect([
            'total_project' => Project::latest()->get()->count(),
            'project_ready' => Project::where('is_ready', 1)->latest()->get()->count(),
            'total_activity' => ProjectActivity::latest()->get()->count(),
        ]);

        return response()->json($data, 200);
    }
}
