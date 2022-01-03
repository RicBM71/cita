<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

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
        'current_password',
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
        // $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
        //     return response()->json([
        //         'responseMessage' => __('You do not have the required authorization.'),
        //         'responseStatus'  => 403,
        //     ]);
        // });

        $this->renderable(function (HttpException $e, $request) {

            if ($request->wantsJson()) {
                return response()->json([
                    'message'    => $e->getMessage(),
                    'statusCode' => $e->getStatusCode(),
                ], $e->getStatusCode());
            }

            if (in_array($e->getStatusCode(), [401, 403, 404, 419, 500, 503])) {
                return Inertia::render('Error/Error' . $e->getStatusCode(),
                    [
                        'statusCode' => $e->getStatusCode(),
                        'message'    => __($e->getMessage()),
                    ]);
            } else {
                return redirect()->back()->withErrors([
                    'message' => $e->getMessage(),
                ]);
            }

        });

        $this->reportable(function (Throwable $e) {

        });
    }

    /**
     * Prepare exception for rendering.
     *
     * @param  \Throwable  $e
     * @return \Throwable
     */
// public function render($request, Throwable $e)
    //     {

//          $response = parent::render($request, $e);

//          if ($request->wantsJson())
    //              return response()->json([
    //                          'message' =>  $e->getMessage(),
    //                          'statusCode'  => $e->getStatusCode(),
    //                      ], $e->getStatusCode());
    //         else{
    //             if (!app()->environment(['local', 'testing']) && in_array($response->status(), [500, 503, 419, 404, 403])) {
    //                 return Inertia::render('Error/Error'.$e->getStatusCode(),
    //                     [
    //                         'statusCode' => $e->getStatusCode(),
    //                         'message' => __($e->getMessage()),
    //                     ])->toResponse($request)
    //                     ->setStatusCode($response->status());

//                 }

//             return $response;
    //         }
    //     }
}
