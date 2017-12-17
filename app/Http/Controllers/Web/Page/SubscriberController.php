<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

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
     * @return bool
     */
    public function storeAction(SubscriberStore $request)
    {
        if (app_settings('subscriber_form')->value == 'enabled') {
            $this->content = Subscriber::store($request->only([
                'email', 'first_name', 'last_name'
            ]));
        }

        return $this->redirectResponse();
    }
}
