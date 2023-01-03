<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function showFile(Request $request, int $fil_ide)
    {
        $file = DB::selectOne('select * from files where fil_ide = ?', [$fil_ide]);
        // return get_resource_type($file->fil_byt);
        
        // $fil_con = pg_unescape_bytea($file->fil_con);
        $fil_con = fread($file->fil_byt, $file->fil_siz);
        return response($fil_con)->header('Content-Type', $file->fil_typ);
    }

    public function upload(Request $request)
    {
        $host = env('DB_HOST');
        $port = env('DB_PORT');
        $dbname = env('DB_DATABASE');
        $user = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $file = $request->file('file');
        $dbcon = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
        // $fil_byt = pg_escape_bytea($dbcon, $file->get());
        $fil_byt = pg_escape_bytea(file_get_contents($file->getRealPath()));
        $fil_ext = $file->extension();
        $fil_typ = $file->getMimeType();
        $fil_siz = $file->getSize();
        $fil_nam = $file->getClientOriginalName();

        $inserted = DB::table('files')->insert([
            'fil_ext' => $fil_ext,
            'fil_typ' => $fil_typ,
            'fil_siz' => $fil_siz,
            'fil_nam' => $fil_nam,
            'fil_byt' => $fil_byt
        ]);

        return !$inserted ?: response('ok', 200);
    }
}
