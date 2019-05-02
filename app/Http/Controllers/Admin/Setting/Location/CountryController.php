<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Setting\Location;

use App\Http\Requests\Admin\Application\Location\CountryStore;
use App\Http\Requests\Admin\Application\Location\CountryUpdate;
use App\Models\Country;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    protected $viewType = 'admin';

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'settings.location.country';
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Countries');
        return $this->view('index', [
            'countries' => Country::fetch(requestValues('search'))
        ]);
    }

    public function createAction()
    {
        $this->setHeader('title', 'Add New Country');
        return $this->view('create');
    }

    public function storeAction(CountryStore $request)
    {
        $country = Country::store($request->all());
        if (!$country) {
            return $this->json(['name' => 'Failed to crate a new country.'], 422, false);
        }

        return $this->json(['redirect' => '/admin/setting/countries']);
    }

    public function editAction($id)
    {
        $country = Country::single($id);
        if (!$country) {
            abort(404);
        }

        $this->setHeader('title', 'Editing ' . $country->name);
        return $this->view('edit', ['country' => $country]);
    }

    public function updateAction(CountryUpdate $request)
    {
        Country::edit($request->get('id'), $request->all());
        return $this->json('Country is successfully updated.');
    }

    public function destroyAction($id)
    {
        Country::remove($id);
        return $this->json('Country is successfully deleted.');
    }
}
