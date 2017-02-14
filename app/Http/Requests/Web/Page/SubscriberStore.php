<?php

namespace App\Http\Requests\Web\Page;

use App\Http\Requests\Web\WebRequestGuest;

class SubscriberStore extends WebRequestGuest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|unique:subscribers,email',
            'first_name' => 'max:100',
            'last_name' => 'max:100'
        ];
    }
}
