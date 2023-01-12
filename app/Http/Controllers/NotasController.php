<?php

namespace App\Http\Controllers;

use App\Models\Notas;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpKernel\Exception\HttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class NotasController extends Controller
{
    public function getAll()
    {
        $data = DB::table('notas');
        $response = Gate::inspect('viewAnyNotas');
        $usu_ide = Auth::id();
        $byUser = $data->where('not_usu', $usu_ide)->paginate(10);
        if ($response->denied()) return $this->response($byUser, 200, $response->message());
        $all = $data->paginate(10);
        return $this->response($all, 200, $response->message());
    }

    public function index()
    {
        return view('form');
    }

    public function getById(int $not_ide)
    {
        // try {
            // $nota = Notas::all()->sole('not_ide', '=', $not_ide);
            $nota = DB::selectOne('select * from notas where not_ide = ?',
            [$not_ide]);
            throw_if(is_null($nota), new HttpException(404));
            //throw_if(is_null($nota), new ItemNotFoundException);
        // } catch (\Illuminate\Support\ItemNotFoundException $_) {
            // return $this->response([], 404, 'Elemento no encontrado');
        // } catch (\Throwable $th) {
            // return $this->response([], 500);
        // }
        $response = Gate::inspect('getNota', [$nota]);
        $message = $response->message();
        $status = $response->code();
        throw_if($response->denied(), new HttpException($status, $message));
        $data = $nota;
        return $this->response($data, $status, $message);
        return $this->response($data, 200, 'Message Empty');
    }

    public function save(Request $request)
    {
        $validator = validator($request->all(), ['not_tit' => 'required|string'], [], ['not_tit' => 'Título']);
        $response = Gate::inspect('createNotas');
        throw_if($response->denied(), new HttpException(403, $response->message()));
        $user = Auth::user();
        if ($this->up($validator->validate())) {
            return $this->response([], 201, 'Nota creada');
        }
        return $this->response([], 500, 'fallo');
    }

    private function up(array $validate)
    {
        $user = Auth::user();
        $data = Arr::add($validate, 'not_usu', $user->usu_ide ?? 2);
        return DB::table('notas')->insert($data);
    }

    public function perform(Request $request)
    {
        $validator = validator(
            $request->all(), 
            [
                'not_tit' => 'required|string',
                'not_des' => 'nullable|string'
            ], 
            [], 
            [
                'not_tit' => 'Titulo',
                'not_des' => 'Descripcion'
            ]
        );
        switch ($request->input('action')) {
            case 'store':
                return $this->store($validator->validate());
            case 'export':
                return $this->exportToExcel($validator->validate());
            default:
                break;
        }
    }

    public function store($validator)
    {
        if ($this->up($validator)) {
            return redirect('notas')->with('success', 'createdo');
        }
        return redirect('notas')->with('error', 'fallo');
    }

    public function update(Request $request, int $not_ide)
    {
        $validator = validator($request->all(), ['not_tit' => 'string', 'not_des' => 'string'], [], ['not_tit' => 'Título']);
        $nota = Notas::all()->sole('not_ide', '=', $not_ide);
        $response = Gate::inspect('updateNotas', [$nota]);
        throw_if($response->denied(), new HttpException(403, $response->message()));
        if ($validator->fails()) {
            return $validator->errors();
        }
        $valid = $validator->validated();
        DB::table('notas')->where('not_ide', $not_ide)->update($valid);
        return $this->response([], 200, 'Nota actualizada');
    }

    public function exportToExcel(array $validate)
    {
        $ss = new Spreadsheet();
        $s = $ss->getActiveSheet();
        $s->setCellValue('A1', 'Titulo');
        $s->setCellValue('A2', Arr::get($validate, 'not_tit'));
        $s->setCellValue('B1', 'Description');
        $s->setCellValue('B2', Arr::get($validate, 'not_des', 'null'));

        $ext = 'Xlsx';
        $write = IOFactory::createWriter($ss, $ext);
        $filename = 'nota';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=$filename.$ext");
        $write->save('php://output');
        exit();
    }
}
