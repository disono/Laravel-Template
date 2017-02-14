<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Controllers\Web\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PageView;

class EventController extends Controller
{
    public function __construct()
    {
        $this->view = 'event.';
        parent::__construct();
    }

    /**
     * Show event details
     *
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($slug)
    {
        $template = 'show';
        $event = Event::single($slug, 'slug');

        if (!$event) {
            abort(404);
        }

        // custom template
        if ($event->template) {
            $template = 'templates.' . $event->template;
        }

        $this->title = $event->name;
        $this->content['event'] = $event;

        PageView::store();
        return $this->response($template);
    }
}
