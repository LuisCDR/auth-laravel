<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Gate;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function response($data, int $status, string $message = '')
    {
        $success = $status <= 400;
        $message = $status == 500 && $message == '' ? 'OCURRIÓ UN ERROR EN EL SISTEMA' : $message;
        $data = [
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'status' => $status
        ];
        return response()->json($data, $status, [], JSON_UNESCAPED_UNICODE);
    }
}
