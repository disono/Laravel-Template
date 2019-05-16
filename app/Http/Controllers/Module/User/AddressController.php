<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\User;

use App\Http\Requests\Module\User\AddressStore;
use App\Http\Requests\Module\User\AddressUpdate;
use App\Models\Country;
use App\Models\UserAddress;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->theme = 'user.settings.address';
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Addresses');
        return $this->view('index', [
            'addresses' => (new UserAddress())->fetch(requestValues('search', ['user_id' => __me()->id]))
        ]);
    }

    public function createAction()
    {
        $this->setHeader('title', 'Create a New Address');
        return $this->view('create', ['countries' => (new Country())->fetchAll()]);
    }

    public function storeAction(AddressStore $request)
    {
        $inputs = $request->all();
        $inputs['user_id'] = $this->me->id;
        if (!(new UserAddress())->store($inputs)) {
            return $this->json(['name' => 'Failed to crate a new address.'], 422, false);
        }

        return $this->json(['redirect' => '/user/setting/addresses']);
    }

    public function editAction($id)
    {
        $this->setHeader('title', 'Editing Address');
        $this->content['address'] = (new UserAddress())->fetch(['id' => $id, 'single' => true, 'user_id' => $this->me->id]);
        if (!$this->content['address']) {
            abort(404);
        }

        $this->content['countries'] = (new Country())->fetchAll();
        return $this->view('edit');
    }

    public function updateAction(AddressUpdate $request)
    {
        (new UserAddress())->edit($request->get('id'), $request->all());
        return $this->json('Address is successfully updated.');
    }

    public function destroyAction($id)
    {
        (new UserAddress())->remove($id);
        return $this->json('Address is successfully deleted.');
    }
}
