<?php

namespace App\Exceptions;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    private $_content = [];

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $_controller = new Controller();
        $_controller->_js();
        $_controller->_seo();
        $this->_content = $_controller->content;

        $e = $this->prepareException($e);
        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        } elseif ($e instanceof AuthenticationException) {
            // authenticated user
            return $this->unauthenticated($request, $e);
        } elseif ($e instanceof ValidationException) {
            // form errors
            return $this->convertValidationExceptionToResponse($e, $request);
        }

        $this->_content['errors'] = $e->getMessage() . ', ' . $e->getLine();
        if ($this->isHttpException($e)) {
            $status = $e->getStatusCode();
            return $this->_viewSelector($status, $status);
        }

        try {
            $this->_content['error_message'] = $this->_content['errors'];
            return $this->_viewSelector('error', 500);
        } catch (\Exception $e) {
            return $this->_viewSelector('error', 500);
        }
    }

    /**
     * Prepare exception for rendering.
     *
     * @param  \Exception $e
     * @return \Exception
     */
    protected function prepareException(Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        } elseif ($e instanceof AuthorizationException) {
            $e = new AccessDeniedHttpException($e->getMessage(), $e);
        } elseif ($e instanceof TokenMismatchException) {
            $e = new HttpException(419, $e->getMessage(), $e);
        }

        return $e;
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param AuthenticationException|\Illuminate\Auth\AuthenticationException $exception
     * @return Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return failed_json_response(exception_messages('MSG_ACCESS'), 401);
        }

        return redirect()->guest(route('login'));
    }

    private function _viewSelector($view, $code = 404)
    {
        if (request()->ajax()) {
            return $this->_jsonResponse($code);
        } else {
            return $this->_viewResponse($view, $code);
        }
    }

    private function _jsonResponse($code = 404)
    {
        return failed_json_response($this->_content['errors'], $code);
    }

    private function _viewResponse($view, $code = 404)
    {
        return response()->view('errors.' . $view, $this->_content, $code);
    }
}
