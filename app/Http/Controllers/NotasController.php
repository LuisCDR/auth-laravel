<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotasController extends Controller
{
    public function getAll()
    {
        $data = DB::table('notas')->paginate(10);
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function save(Request $request)
    {
        $validator = validator($request->all(), ['not_tit' => 'required|string']);
        $user = Auth::user();
        if ($validator->fails()) {
            return $validator->errors();
        }
        $data = Arr::add($validator->validated(), 'not_usu', $user->usu_ide);
        DB::table('notas')->insert($data);
        return response()->json([
            'success' => true
        ]);
    }
}
