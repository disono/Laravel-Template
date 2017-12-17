<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class ViewTemplatesController extends Controller
{
    public function show()
    {
        if ($this->request->get('type') == 'delete') {
            return view('modals.delete');
        } else if ($this->request->get('type') == 'media-chooser') {
            return view('modals.media-chooser');
        }

        return abort(404);
    }
}
