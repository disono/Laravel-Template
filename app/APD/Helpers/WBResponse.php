<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

use App\APDApp\Helpers\libs\VideoStream;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Intervention\Image\Facades\Image;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

if (!function_exists('successJSONResponse')) {
    /**
     * JSON success response
     *
     * @param array $data
     * @param null $links
     * @param null $pagination
     * @return JsonResponse
     */
    function successJSONResponse($data = [], $links = null, $pagination = null)
    {
        if (is_array($data) || is_string($data) || is_numeric($data) || !$data instanceof LengthAwarePaginator) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'pagination' => null,
                'links' => $links
            ], 200);
        }

        $data = ($data) ? ((method_exists($data, 'toArray')) ? $data->toArray() : (array)$data) : $data;
        if (!$data) {
            return failedJSONResponse('404 data not found.', 404);
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
            'links' => $links
        ], 200);
    }
}

if (!function_exists('failedJSONResponse')) {
    /**
     * Failed JSON response
     *
     * @param array $errors
     * @param int $status
     * @param bool $default_message
     * @return JsonResponse
     */
    function failedJSONResponse($errors = [], $status = 422, $default_message = true)
    {
        $error_messages = $errors;

        $clean_errors = [];
        if (is_array($errors) && $default_message) {
            foreach ($error_messages as $name => $message) {
                $clean_errors[$name] = $message[0];
            }

            $error_messages = $clean_errors;
        } else if (!$default_message) {
            $error_messages = $errors;
        }

        return response()->json([
            'success' => false,
            'errors' => $error_messages
        ], $status);
    }
}

if (!function_exists('httpDownloadImage')) {
    /**
     * Download image from URI
     *
     * @param $file_name
     * @param null $url
     * @param int $quality
     * @return null|string
     */
    function httpDownloadImage($file_name, $url = null, $quality = 75)
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

                if (copy($http, 'private/' . $file_name)) {
                    $image = Image::make('private/' . $file_name);

                    $image->save(null, $quality);
                    return $file_name;
                }
            }
        } catch (\Exception $e) {

        }

        return null;
    }
}

if (!function_exists('ipAddress')) {
    /**
     * Current IP Address
     *
     * @return null
     */
    function ipAddress()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : null;
        }

        return $ip;
    }
}

if (!function_exists('userAgent')) {
    /**
     * Get User Agent
     *
     * @return null
     */
    function userAgent()
    {
        $object = new \stdClass();
        $object->u_agent = $_SERVER['HTTP_USER_AGENT'];
        $object->browserName = 'Unknown';
        $object->platform = 'Unknown';
        $object->version = "Unknown";

        //First get the platform?
        if (preg_match('/linux/i', $object->u_agent)) {
            $object->platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $object->u_agent)) {
            $object->platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $object->u_agent)) {
            $object->platform = 'windows';
        }

        // Next get the name of the user-agent yes separately and for good reason
        if (preg_match('/MSIE/i', $object->u_agent) && !preg_match('/Opera/i', $object->u_agent)) {
            $object->browserName = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $object->u_agent)) {
            $object->browserName = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/OPR/i', $object->u_agent)) {
            $object->browserName = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Chrome/i', $object->u_agent)) {
            $object->browserName = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $object->u_agent)) {
            $object->browserName = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Netscape/i', $object->u_agent)) {
            $object->browserName = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        try {
            $known = array('Version', $ub, 'other');
            $object->pattern = '#(?<browser>' . join('|', $known) .
                ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
            if (!preg_match_all($object->pattern, $object->u_agent, $matches)) {
                // we have no matching number just continue
            }

            // see how many we have
            $i = count($matches['browser']);
            if ($i != 1) {
                //we will have two since we are not using 'other' argument yet
                //see if version is before or after the name
                if (strripos($object->u_agent, "Version") < strripos($object->u_agent, $ub)) {
                    $object->version = $matches['version'][0];
                } else {
                    $object->version = $matches['version'][1];
                }
            } else {
                $object->version = $matches['version'][0];
            }
        } catch (\Exception $e) {
            $object->pattern = '';
            $object->version = null;
        }

        // check if we have a number
        if ($object->version == null || $object->version == "") {
            $object->version = "?";
        }

        return $object;
    }
}

if (!function_exists('FCMSend')) {
    /**
     * Send FMC message
     *
     * @param $token
     * @param $title
     * @param $body
     * @param string $sound
     * @return bool
     */
    function FCMSend($token, $title, $body, $sound = 'default')
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)->setSound($sound);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();

        $downstreamResponse = FCM::sendTo($token, $option, $notification);
        if ($downstreamResponse->numberFailure()) {
            return false;
        }

        return true;
    }
}

if (!function_exists('FCMTopic')) {
    /**
     * Send FCM topic
     *
     * @param $topic_name
     * @param $title
     * @param $body
     * @param string $sound
     * @return mixed
     */
    function FCMTopic($topic_name, $title, $body, $sound = 'default')
    {
        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)->setSound($sound);
        $notification = $notificationBuilder->build();

        $topic = new Topics();
        $topic->topic($topic_name);

        return FCM::sendToTopic($topic, null, $notification, null)->isSuccess();
    }
}

if (!function_exists('videoStream')) {
    /**
     * Stream video file
     *
     * @param null $file_path
     * @return ResponseFactory|Response|StreamedResponse
     */
    function videoStream($file_path = null)
    {
        if (file_exists($file_path)) {
            $stream = new VideoStream($file_path);
            return response()->stream(function () use ($stream) {
                $stream->start();
            });
        }

        return response("File doesn't exists", 404);
    }
}

if (!function_exists('devAssets')) {
    /**
     * Create random url extension for assets
     *
     * @param $path
     * @return null|string
     */
    function devAssets($path)
    {
        if (env('APP_ENV') == 'local') {
            return asset($path) . devURLExt();
        }

        return asset($path) . '?' . __settings('themeVersion')->value;
    }
}

if (!function_exists('devURLExt')) {
    /**
     * For dev purposes only (url)
     *
     * @return integer
     */
    function devURLExt()
    {
        try {
            if (!request()) {
                return null;
            }

            $css_version = __settings('themeVersion')->value;
            if (request()->session()->has('themeVersion') && env('APP_ENV') != 'local') {
                if (request()->session()->get('themeVersion') != $css_version) {
                    $version = rand(10, 100) . time();
                    $css_version = '?' . $version;

                    // store the current css version
                    request()->session()->put('themeVersion', $version);
                }
            }

            return (env('APP_ENV') == 'local') ? '?' . rand(10, 100) . time() : '?' . $css_version;
        } catch (\Exception $e) {
            logErrors($e->getMessage());
            return null;
        }
    }
}

if (!function_exists('requestOptions')) {
    /**
     * Request values
     *
     * @param array $inputs
     * @param array $defaultValues
     * @return array
     */
    function requestValues($inputs = [], $defaultValues = [])
    {
        $values = [];
        $request = request();

        // if inputs is strings
        if (is_string($inputs)) {
            $inputs = preg_replace('/\s*/m', '', $inputs);
            $inputs = explode('|', $inputs);
        }

        if ($inputs) {
            foreach ($inputs as $key) {
                if ($request->get($key) !== null && $request->get($key) !== '') {
                    $values[$key] = $request->get($key);
                }
            }
        }

        if (count($defaultValues)) {
            $values = array_merge($values, $defaultValues);
        }

        return $values;
    }
}

if (!function_exists('fetchRequestValue')) {
    /**
     * Fetch the header or request value
     *
     * @param $key
     * @param null $default
     * @return array|Request|string
     */
    function fetchRequestValue($key, $default = null)
    {
        return (request()->header($key)) ? request()->header($key) : request($key, $default);
    }
}
