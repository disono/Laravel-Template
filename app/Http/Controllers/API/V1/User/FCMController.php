<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\API\APIController;
use App\Models\FirebaseToken;
use App\Models\Token;

class FCMController extends APIController
{
    public function storeAction()
    {
        if (!Token::find($this->request->get('token_id')) || !$this->request->get('fcm_token')) {
            return $this->json('Invalid token source or FCM is invalid.', 422);
        }

        if (FirebaseToken::single($this->request->get('token_id'), 'token_id')) {
            return $this->json(FirebaseToken::edit(null, [
                'fcm_token' => $this->request->get('fcm_token')
            ], [
                'token_id' => $this->request->get('token_id')
            ]));
        }

        return $this->json(FirebaseToken::store([
            'token_id' => $this->request->get('token_id'),
            'fcm_token' => $this->request->get('fcm_token')
        ]));
    }
}
