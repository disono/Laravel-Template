<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

abstract class Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get all errors if validation failed
     *
     * @param array $errors
     * @return \Illuminate\Http\RedirectResponse
     */
    public function response(array $errors)
    {
        if ($this->expectsJson()) {
            return failed_json_response($errors);
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }

    /**
     * Invalid credentials
     *
     * @return Response
     */
    public function forbiddenResponse()
    {
        if ($this->expectsJson()) {
            return failed_json_response(env('MSG_ACCESS'), 401);
        }

        return new Response('Forbidden', 403);
    }
}
