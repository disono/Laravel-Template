<?php

namespace App\Http\Controllers\Web\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Page\SubscriberStore;
use App\Models\Subscriber;

class SubscriberController extends Controller
{
    /**
     * Subscribe
     *
     * @param SubscriberStore $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SubscriberStore $request)
    {
        if (app_settings('subscriber_form')->value == 'enabled') {
            Subscriber::store($request->only([
                'email', 'first_name', 'last_name'
            ]));
        }

        return redirect()->back();
    }
}
