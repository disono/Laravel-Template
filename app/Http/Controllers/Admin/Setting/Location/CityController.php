<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Setting\Location;

use App\Http\Requests\Admin\Application\Location\CityStore;
use App\Http\Requests\Admin\Application\Location\CityUpdate;
use App\Models\City;
use App\Http\Controllers\Controller;
use App\Models\Country;

class CityController extends Controller
{
    protected $viewType = 'admin';

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'settings.location.city';
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Cities');
        return $this->view('index', [
            'cities' => (new City())->fetch(requestValues('search|country_id'))
        ]);
    }

    public function createAction()
    {
        $this->setHeader('title', 'Add New Country');

        $country = null;
        if ($this->request->get('country_id')) {
            $country = (new Country())->single($this->request->get('country_id'));
            if (!$country) {
                abort(404);
            }
        }

        return $this->view('create', ['countries' => (new Country())->fetchAll(), 'country' => $country]);
    }

    public function storeAction(CityStore $request)
    {
        $city = (new City())->store($request->all());
        if (!$city) {
            return $this->json(['name' => 'Failed to crate a new city.'], 422, false);
        }

        return $this->json(['redirect' => '/admin/setting/cities?country_id=' . $city->country_id]);
    }

    public function editAction($id)
    {
        $city = (new City())->single($id);
        if (!$city) {
            abort(404);
        }

        $this->setHeader('title', 'Editing ' . $city->name);
        return $this->view('edit', ['city' => $city, 'countries' => (new Country())->fetchAll()]);
    }

    public function updateAction(CityUpdate $request)
    {
        (new City())->edit($request->get('id'), $request->all());
        return $this->json('City is successfully updated.');
    }

    public function destroyAction($id)
    {
        (new City())->remove($id);
        return $this->json('City is successfully deleted.');
    }
}
