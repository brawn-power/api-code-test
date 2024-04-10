<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Throwable;
use App\Traits\JsonResponse as AppJsonResponse;
use Dotenv\Exception\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class Handler extends ExceptionHandler
{
    use AppJsonResponse;

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        $parentRender = parent::render($request, $e);
        $statusCode = $parentRender->getStatusCode();

        // if parent returns a JsonResponse
        // for example in case of a ValidationException
        if ($parentRender instanceof JsonResponse) {
            $message = 'Internal Server Error';
            $data = $parentRender->getData(true);
            if (isset($data['message'])) {
                $message = $data['message'];
            }
            switch ($statusCode) {
                case 422:
                    try {
                        foreach ($parentRender->getData() as $error) {
                            foreach ($error as $k => $v) {
                                $message = $v;
                                break;
                            }
                        }
                    } catch (\Exception $e) {
                        $message = 'Invalid params';
                    }
                    break;
                case 405:
                    $message = 'Method Not Allowed';
                    break;
                default:
            }
            return $this->error($message, $statusCode);
        }
        
        return $this->error($e->getMessage(), $statusCode);
    }
}
