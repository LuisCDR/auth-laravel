<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ItemNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                report($e);
                Log::emergency("NO SE ENCONTRÓ EL RECURSO, - {$request->fullUrl()}");
                return $this->response(404, "NO SE ENCONTRÓ EL RECURSO");
            }
        })
        ->renderable(function (HttpException $e) {
            $status = $e->getStatusCode();
            $message = $e->getMessage();
            if ($status == 404) {
                $message = "NO SE ENCONTRÓ EL RECURSO";
                $status = 404;
            } else if($status == 403) {
                $message = $e->getMessage();
            }
            return $this->response($status, $message);
        })
        ->renderable(function (ItemNotFoundException $e) {
            report($e);
            return $this->response(404, 'ELEMENTO NO ENCONTRADO');
        })
        ->renderable(function (QueryException $e) {
            report($e);
            return $this->response(500, "System Error: {$e->getMessage()}", $e->getTrace());
        });
    }

    private function response(int $status, string $message = '', array $data = [])
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
            'status' => $status
        ], $status, [], JSON_UNESCAPED_UNICODE);
    }
}
