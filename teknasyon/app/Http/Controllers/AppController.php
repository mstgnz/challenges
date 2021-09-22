<?php

namespace App\Http\Controllers;

use App\Classes\CResult;
use App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppController extends Controller
{
    //
    public function index(Request $request)
    {
        $apps = App::all();
        return CResult::success($apps);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'endpoint' => 'required',
        ]);
        if ($validator->fails()) {
            return CResult::error($validator->errors(), "error", 400);
        } else {
            $insertApp = App::firstOrCreate([
                'name' => $request->name
            ], [
                'name' => $request->name,
                'endpoint' => $request->endpoint
            ]);
            if ($insertApp) {
                return CResult::success($insertApp);
            } else {
                return CResult::error([], 'Böyle bir app mevcut.');
            }
        }
    }
}
