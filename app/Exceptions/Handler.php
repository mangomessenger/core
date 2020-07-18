<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }
        return $this->handleApiException($request, $exception);
    }

    private function handleApiException($request, \Exception $exception)
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof HttpResponseException) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof InvalidPayloadException) {
            return response()->json(['type' => $exception->type, 'message' => $exception->getMessage(), 'errors' => $exception->getErrors(), 'status' => $exception->getCode()]);
        }

        if ($exception instanceof ApiException) {
            return response()->json(['type' => $exception->type, 'message' => $exception->getMessage(), 'status' => $exception->getCode()], $exception->getCode());
        }

        return $this->customApiResponse($exception);
    }

    private function customApiResponse($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }

        $response = [];

        switch ($statusCode) {
            case 401:
                $response['type'] = 'UNAUTHORIZED';
                $response['message'] = 'Authentication is required and has failed or has not yet been provided.';
                break;
            case 403:
                $response['type'] = 'FORBIDDEN';
                $response['message'] = 'The request was understood by the server, but the server is refusing action.';
                break;
            case 404:
                $response['type'] = 'NOT_FOUND';
                $response['message'] = 'The requested resource could not be found but may be available in the future.';
                break;
            case 405:
                $response['type'] = 'METHOD_NOT_ALLOWED';
                $response['message'] = 'A request method is not supported for the requested resource.';
                break;
            case 422:
                $response['type'] = 'INVALID_PAYLOAD';
                $response['message'] = $exception->original['message'];
                $response['errors'] = $exception->original['errors'];
                break;
            case 429:
                $response['type'] = 'FLOOD_TIMEOUT_' . $exception->timeout;
                $response['message'] = $exception->getMessage();
                break;
            case 500:
                $response['type'] = 'INTERNAL';
                $response['message'] = 'Whoops, looks like something went wrong';
                break;

            default:
                $response['type'] = 'UNEXPECTED_ERROR';
                $response['message'] = $exception->getMessage();
                break;
        }

        $response['status'] = $statusCode;

        return response()->json($response, $statusCode);
    }
}
