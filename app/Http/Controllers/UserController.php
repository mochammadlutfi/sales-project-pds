<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Settings\GeneralSetting;
use App\Models\Locale;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $data = auth()->user();
        return response()->json($data);
    }

    public function setLang($lang)
    {
        App::setLocale($lang);
    }

}