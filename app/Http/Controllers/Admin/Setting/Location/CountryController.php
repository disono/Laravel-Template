<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Setting\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Application\Location\CountryStore;
use App\Http\Requests\Admin\Application\Location\CountryUpdate;
use App\Models\Vendor\Facades\Country;

class CountryController extends Controller
{
    protected $viewType = 'admin';
    private $_country;

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'settings.location.country';
        $this->_country = Country::self();
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Countries');
        $this->_country->enableSearch = true;
        return $this->view('index', [
            'countries' => $this->_country->fetch(requestValues('search|pagination_show|name|code'))
        ]);
    }

    public function createAction()
    {
        $this->setHeader('title', 'Add New Country');
        return $this->view('create');
    }

    public function storeAction(CountryStore $request)
    {
        $country = $this->_country->store($request->all());
        if (!$country) {
            return $this->json(['name' => 'Failed to crate a new country.'], 422, false);
        }

        return $this->json(['redirect' => '/admin/setting/countries']);
    }

    public function editAction($id)
    {
        $country = $this->_country->single($id);
        if (!$country) {
            abort(404);
        }

        $this->setHeader('title', 'Editing ' . $country->name);
        return $this->view('edit', ['country' => $country]);
    }

    public function updateAction(CountryUpdate $request)
    {
        $this->_country->edit($request->get('id'), $request->all());
        return $this->json('Country is successfully updated.');
    }

    public function destroyAction($id)
    {
        $this->_country->remove($id);
        return $this->json('Country is successfully deleted.');
    }
}
