<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
 */
namespace App\Library\Helpers;

use ElephantIO\Client as Elephant;
use Intervention\Image\Facades\Image;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;

class WBHttp
{
    /**
     * Success JSON response
     *
     * @param array $data
     * @param null $links
     * @param null $pagination
     * @param null $extra
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function successJSONResponse($data = [], $links = null, $pagination = null, $extra = null)
    {
        if (is_array($data) || is_string($data) || is_numeric($data)) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'links' => $links,
                'extra' => $extra
            ], 200);
        }

        $data = ($data) ? ((method_exists($data, 'toArray')) ? $data->toArray() : (array)$data) : $data;
        if (!$data) {
            return self::failedJSONResponse('Data not found.', 404);
        }

        if (isset($data['data'])) {
            if (gettype($data['data']) === 'array') {
                // pagination
                $pagination = [
                    'total' => (isset($data['total'])) ? $data['total'] : null,
                    'per_page' => (isset($data['per_page'])) ? $data['per_page'] : null,
                    'previous_page' => (isset($data['current_page'])) ? $data['current_page'] - 1 : null,
                    'current_page' => (isset($data['current_page'])) ? $data['current_page'] : null,
                    'next_page' => (isset($data['current_page'])) ? $data['current_page'] + 1 : null,
                    'last_page' => (isset($data['last_page'])) ? $data['last_page'] : null,
                    'next_page_url' => (isset($data['next_page_url'])) ? $data['next_page_url'] : null,
                    'prev_page_url' => (isset($data['prev_page_url'])) ? $data['prev_page_url'] : null,
                    'from' => (isset($data['from'])) ? $data['from'] : null,
                    'to' => (isset($data['to'])) ? $data['to'] : null
                ];

                // main data
                $data = $data['data'];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'pagination' => $pagination,
            'links' => $links,
            'extra' => $extra
        ], 200);
    }

    /**
     * Failed JSON response
     *
     * @param array $errors
     * @param int $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function failedJSONResponse($errors = [], $status = 422)
    {
        $is_array = is_array($errors);
        $error_messages = $errors;

        $clean_errors = [];
        if ($is_array) {
            foreach ($error_messages as $name => $message) {
                $clean_errors[$name] = $message[0];
            }

            $error_messages = $clean_errors;
        }

        return response()->json([
            'success' => false,
            'errors' => $error_messages
        ], $status);
    }

    /**
     * Download image from remote
     *
     * @param $file_name
     * @param $url
     * @param int $quality
     *
     * @return bool
     */
    public static function downloadImage($file_name, $url = null, $quality = 75)
    {
        if (!$url) {
            return null;
        }

        try {
            // don't use https
            $http = str_replace('https://', 'http://', $url);

            // get image
            $image = @getimagesize($http);

            // download now
            if ($http && $image) {
                $file_name = $file_name . image_type_to_extension($image[2]);

                if (copy($http, 'private/img/' . $file_name)) {
                    $image = Image::make('private/img/' . $file_name);

                    $image->save(null, $quality);
                    return $file_name;
                }
            }
        } catch (\Exception $e) {
            // log errors
        }

        return null;
    }

    /**
     * IP Address
     *
     * @return mixed
     */
    public static function ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    /**
     * Get user agent
     *
     * @return null
     */
    public static function userAgent()
    {
        return (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : null;
    }

    /**
     * NodeJS Connector
     *
     * @param null $path
     *
     * @return mixed
     */
    public static function NodeJSConnector($path = null)
    {
        $json = null;
        try {
            echo env('NODEJS_URI');
            $json = file_get_contents(env('NODEJS_URI') . $path);
            return json_decode($json);
        } catch (\Exception $e) {
            error_logger($e->getMessage());
        }

        return null;
    }

    /**
     * SocketIO Emit
     *
     * @param $name
     * @param array $data
     * @param null $uri
     */
    public static function SocketIOEmit($name, $data = [], $uri = null)
    {
        if (!$uri) {
            $uri = env('NODEJS_IO');
        }

        try {
            $elephant = new Elephant(new \ElephantIO\Engine\SocketIO\Version1X($uri));
            $elephant->initialize();
            $elephant->emit($name, $data);
            $elephant->close();
        } catch (\Exception $e) {
            error_logger($e->getMessage());
        }
    }

    /**
     * FCM Send
     *
     * @param $token
     * @param $title
     * @param $body
     * @param string $sound
     *
     * @return bool
     */
    public static function FCMSend($token, $title, $body, $sound = 'default')
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
            ->setSound($sound);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();

        $downstreamResponse = FCM::sendTo($token, $option, $notification);

        if ($downstreamResponse->numberFailure()) {
            return false;
        }

        return true;
    }

    /**
     * Send topic
     *
     * @param $topic_name
     * @param $title
     * @param $body
     * @param string $sound
     * @return mixed
     */
    public static function FCMTopic($topic_name, $title, $body, $sound = 'default')
    {
        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
            ->setSound($sound);

        $notification = $notificationBuilder->build();

        $topic = new Topics();
        $topic->topic($topic_name);

        $topicResponse = FCM::sendToTopic($topic, null, $notification, null);

        return $topicResponse->isSuccess();
    }
}