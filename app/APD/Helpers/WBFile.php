<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

use App\Models\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

if (!function_exists('fileUpload')) {
    /**
     * Upload file
     *
     * @param $options = [file, filename, destination, options, title, desc, tableName, tableId]
     *
     * @return array
     */
    function fileUpload($options)
    {
        $file = $options['file'];
        $files = [];

        if (is_array($file)) {
            foreach ($file as $request) {
                $uploaded = fileSave($request, $options);
                if ($uploaded) {
                    $files[] = $uploaded;
                }
            }
        } else {
            $uploaded = fileSave($file, $options);
            if ($uploaded) {
                $files[] = $uploaded;
            }
        }

        return $files;
    }
}

if (!function_exists('fileSave')) {
    /**
     * Save file to database
     *
     * @param $file
     * @param $options = [filename, destination, options, title, desc, tableName, tableId]
     *
     * @return null
     */
    function fileSave($file, $options)
    {
        $destination = $options['destination'] ?? 'private';
        $title = $options['title'] ?? null;
        $description = $options['desc'] ?? null;
        $fileOptions = $options['options'] ?? [];
        $tableName = $options['tableName'] ?? null;
        $tableId = $options['tableId'] ?? 0;

        $file = processUpload($file, $destination, $title, $description);
        if ($file->fileName && $file->type) {
            // process image
            if (count($fileOptions) && $file->type === 'photo') {
                processImage($destination . '/' . $file->fileName, $fileOptions);
            }

            // save to database
            $user_id = (__me()) ? __me()->id : 0;
            $fileSave = (new File())->store([
                'user_id' => $user_id,
                'file_name' => $file->fileName,
                'type' => $file->type,
                'ext' => $file->ext,
                'title' => $file->title,
                'description' => $description,
                'table_name' => $tableName,
                'table_id' => $tableId,
                'tag' => retrievedFileOption($options, 'tag')
            ]);

            if ($fileSave) {
                $file->db = $fileSave;
                return $file;
            } else {
                // delete if not successful saving to database
                fileDestroy($destination . '/' . $file->fileName);
            }
        }

        return null;
    }
}

if (!function_exists('retrievedFileOption')) {
    /**
     * Options
     *
     * @param $options
     * @param $key
     * @return null
     */
    function retrievedFileOption($options, $key)
    {
        if (isset($options['options'])) {
            $_options = $options['options'];

            if (isset($_options[$options['filename']])) {
                $_fileOptions = $_options[$options['filename']];

                if (isset($_fileOptions[$key])) {
                    return $_fileOptions[$key];
                }
            }
        }

        return null;
    }
}

if (!function_exists('processUpload')) {
    /**
     * Profile the file to upload
     *
     * @param $file
     * @param $destinationPath
     * @param $title
     * @param $description
     *
     * @return stdClass
     */
    function processUpload($file, $destinationPath = 'private', $title = null, $description = null)
    {
        $object = new stdClass();
        $object->fileName = null;
        $object->ext = null;
        $object->type = null;
        $object->title = $title;
        $object->description = $description;
        $object->db = null;

        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $uploadFilename = str_random(16) . '-' . time() . '.' . $extension;
            $file->move($destinationPath, $uploadFilename);

            $object->fileName = $uploadFilename;
            $object->ext = $extension;
            $object->type = getFileType($extension);
        }

        return $object;
    }
}

if (!function_exists('processImage')) {
    /**
     * Process the image
     *
     * @param $path
     * @param array $fileOptions
     */
    function processImage($path, $fileOptions = [])
    {
        $image = Image::make($path);
        if ($image) {
            // crop
            if (isset($fileOptions['crop_width']) && isset($fileOptions['crop_height'])) {
                $fileOptions->crop((int)$fileOptions['crop_width'], (int)$fileOptions['crop_height']);
            } else if (isset($fileOptions['crop_auto'])) {
                $height = $image->height() * 0.85;
                $width = $height;
                $image->crop((int)$width, (int)$height);
            }

            // resize
            if (isset($fileOptions['width']) && isset($fileOptions['height'])) {
                $image->resize((int)$fileOptions['width'], (int)$fileOptions['height']);
            }

            // resize only the height of the image
            if (isset($fileOptions['heightRatio'])) {
                $image->resize(null, (int)$fileOptions['heightRatio'], function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            // resize only the width of the image
            if (isset($fileOptions['widthRatio'])) {
                $image->resize(null, (int)$fileOptions['widthRatio'], function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            // save
            $quality = (isset($fileOptions['quality'])) ? $fileOptions['quality'] : 100;
            $image->save(null, imageQuality($image->filesize(), $quality));
        }
    }
}

if (!function_exists('getFileType')) {
    /**
     * Get the file type
     *
     * @param $extension
     *
     * @return string
     */
    function getFileType($extension)
    {
        $extension = strtolower($extension);
        if (in_array($extension, ['jpeg', 'jpg', 'png', 'gif', 'tiff', 'bmp'])) {
            return 'photo';
        } else if (in_array($extension, ['mpeg', 'mp4', 'avi', 'wmv', 'mov'])) {
            return 'video';
        } else if (in_array($extension, ['mp3'])) {
            return 'audio';
        } else if (in_array($extension, ['pdf', 'doc', 'docx', 'txt', 'ppt', 'pptx', 'tiff', 'odt', 'ods', 'xls', 'xlsx'])) {
            return 'doc';
        } else if (in_array($extension, ['zip', 'rar'])) {
            return 'file';
        }

        return null;
    }
}

if (!function_exists('fileDestroy')) {
    /**
     * Delete file
     *
     * @param $path
     *
     * @return bool
     */
    function fileDestroy($path)
    {
        $file = explode('/', $path);

        if (count($file)) {
            $file_name = $file[count($file) - 1];
            if ($file_name == null || $file_name == '') {
                return false;
            }
        }

        if (!file_exists($path)) {
            return true;
        }

        if (file_exists($path)) {
            return (bool)unlink($path);
        }

        return false;
    }
}

if (!function_exists('imageQuality')) {
    /**
     * Reduce quality to 85% if file-size is more than 3MB
     *
     * @param $fileSize
     *  Bytes
     * @param int $quality
     *  Integer form 1 to 100
     *
     * @return int|null
     */
    function imageQuality($fileSize, $quality = 85)
    {
        $default = __settings('fileSizeLimitImage')->value;
        $default = ($default) ? ((int)$default * 1000) : 3000000;

        if ($fileSize > $default) {
            return $quality;
        } else {
            return 100;
        }
    }
}

if (!function_exists('createFolder')) {
    /**
     * Create a folder
     *
     * @param $path
     * @return bool
     */
    function createFolder($path)
    {
        // check if folder exists
        if (!file_exists($path)) {
            return mkdir($path);
        }

        return true;
    }
}

if (!function_exists('fetchImage')) {
    /**
     * Get image
     *
     * @param $source
     * @param null $default
     * @param bool $pathOnly
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|null|string
     */
    function fetchImage($source, $default = null, $pathOnly = false)
    {
        $image = null;
        $path = null;
        if (is_numeric($source)) {
            $image = File::find($source);
        }

        $filename = null;
        if ($image) {
            $filename = $image->file_name;
        } else {
            $filename = $source;
        }

        // complete path for image
        $complete_path = 'private/' . $filename;

        // check if folder exists
        if (!file_exists('private')) {
            if (!file_exists('private')) {
                mkdir('private');
            }

            // create the folder
            createFolder('private');
        }

        // add the path
        if (file_exists($complete_path) && $filename != null) {
            $path = $complete_path;
        } else {
            $path = $default;
        }

        return ($pathOnly) ? $path : url($path);
    }
}

if (!function_exists('logErrors')) {
    /**
     * Log Errors
     *
     * @param $message
     */
    function logErrors($message)
    {
        $message = (is_string($message)) ? $message : (string)$message;

        Log::error(
            '(Date: ' . date('M d Y h:i:s A', time()) .
            ' - Error Message: ' . $message .
            ' - Request Path: ' .
            "http://" . ((isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) ? $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : request()->getRequestUri())
        );
    }
}

if (!function_exists('imgPathToBase64')) {
    /**
     * Convert image path to base64 image
     *
     * @param $path
     * @return string
     */
    function imgPathToBase64($path)
    {
        try {
            // A few settings
            $img_file = $path;

            // Read image path, convert to base64 encoding
            $imgData = base64_encode(file_get_contents($img_file));

            // Format the image SRC:  data:{mime};base64,{data};
            $src = 'data: ' . mime_content_type($img_file) . ';base64,' . $imgData;

            return $src;
        } catch (Exception $e) {
            return $path;
        }
    }
}
