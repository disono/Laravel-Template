<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

if (!function_exists('socketIOPublish')) {
    /**
     * Publish data to socketIO
     *
     * @param $to
     * @param array $data
     * @return bool|string
     */
    function socketIOPublish($to, $data = [])
    {
        if (__settings('socketIO')->value !== 'enabled') {
            return 'Socket IO is not enabled.';
        }

        try {
            $client = new Client(new Version2X(__settings('socketIOServer')->value, [
                'headers' => [
                    'Authorization: Bearer ' . cryptoJsAesEncrypt(
                        __settings('socketIOSecretKey')->value,
                        strtotime('+' . __settings('socketIOExpiration')->value . ' minutes', time()) . '.' . md5(__settings('socketIOAppName')->value)
                    ),
                    'App-Name: ' . __settings('socketIOAppName')->value,
                    'Source: ' . 'server'
                ]
            ]));
            $client->initialize();
            $client->emit(__settings('socketIOAppName')->value . '_onServerSubscribe', [
                'to' => $to,
                'data' => $data
            ]);
            $client->close();

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}