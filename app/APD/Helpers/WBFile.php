<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

if (!function_exists('fileUpload')) {
    /**
     * Upload file
     *
     * @param $file
     * @param string $destinationPath
     * @param array $imageOptions
     * @param null $title
     * @param null $description
     * @param null $tableName
     * @param int $tableId
     *
     * @return array
     */
    function fileUpload($file, $destinationPath = 'private', $imageOptions = [], $title = null, $description = null, $tableName = null, $tableId = 0)
    {
        $files = [];
        if (is_array($file)) {
            foreach ($file as $value) {
                $uploaded = fileSave($value, $destinationPath, $imageOptions, $title, $description, $tableName, $tableId);
                if ($uploaded) {
                    $files[] = $uploaded;
                }
            }
        } else {
            $uploaded = fileSave($file, $destinationPath, $imageOptions, $title, $description, $tableName, $tableId);
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
     * @param string $destinationPath
     * @param array $imageOptions
     * @param null $title
     * @param null $description
     * @param null $tableName
     * @param int $tableId
     *
     * @return null
     */
    function fileSave($file, $destinationPath = 'private', $imageOptions = [], $title = null, $description = null, $tableName = null, $tableId = 0)
    {
        $file = processUpload($file, $destinationPath, $title, $description);
        if ($file->fileName) {
            // process image
            if (count($imageOptions) && $file->type === 'photo') {
                processImage($destinationPath . '/' . $file->fileName, $imageOptions);
            }

            // save to database
            $user_id = (__me()) ? __me()->id : 0;
            $fileSave = \App\Models\File::store([
                'user_id' => $user_id,
                'file_name' => $file->fileName,
                'type' => $file->type,
                'ext' => $file->ext,
                'title' => $file->title,
                'description' => $description,
                'table_name' => $tableName,
                'table_id' => $tableId,
                'tag' => ($file->type === 'photo') ? hasImageTag($imageOptions) : null
            ]);

            if ($fileSave) {
                $file->db = $fileSave;
                return $file;
            } else {
                // delete if not successful saving to database
                fileDestroy($destinationPath . '/' . $file->fileName);
            }
        }

        return null;
    }
}

if (!function_exists('hasImageTag')) {
    /**
     * Get the tag for image option
     *
     * @param $imageOptions
     * @return mixed|null
     */
    function hasImageTag($imageOptions)
    {
        $tag = $imageOptions['tag'] ?? null;

        if (is_array($tag)) {
            foreach ($tag as $key => $value) {
                if (request()->hasFile($key)) {
                    return $value;
                }
            }
        }

        return $tag;
    }
}

if (!function_exists('processUpload')) {
    /**
     * Profile the file to upload
     *
     * @param $file
     * @param string $destinationPath
     * @param null $title
     * @param null $description
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
     * @param array $imageOptions
     */
    function processImage($path, $imageOptions = [])
    {
        $image = Image::make($path);
        if ($image) {
            // crop
            if (isset($imageOptions['crop_width']) && isset($imageOptions['crop_height'])) {
                $imageOptions->crop((int)$imageOptions['crop_width'], (int)$imageOptions['crop_height']);
            } else if (isset($imageOptions['crop_auto'])) {
                $height = $imageOptions->height() * 0.85;
                $width = $height;
                $image->crop((int)$width, (int)$height);
            }

            // resize
            if (isset($imageOptions['width']) && isset($imageOptions['height'])) {
                $image->resize((int)$imageOptions['width'], (int)$imageOptions['height']);
            }

            // resize only the height of the image
            if (isset($imageOptions['heightRatio'])) {
                $image->resize(null, (int)$imageOptions['heightRatio'], function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            // resize only the width of the image
            if (isset($imageOptions['widthRatio'])) {
                $image->resize(null, (int)$imageOptions['widthRatio'], function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            // save
            $quality = (isset($imageOptions['quality'])) ? $imageOptions['quality'] : 100;
            $image->save(null, imageQuality($image->filesize(), $quality));
        }
    }
}

if (!function_exists('getFileType')) {
    /**
     * Get the file type
     *
     * @param $extension
     * @return string
     */
    function getFileType($extension)
    {
        $extension = strtolower($extension);
        if (in_array($extension, ['jpeg', 'jpg', 'png', 'gif', 'tiff', 'bmp'])) {
            return 'photo';
        } else if (in_array($extension, ['mpeg', 'mp4', 'avi', 'wmv', 'mov'])) {
            return 'video';
        } else if (in_array($extension, ['pdf', 'doc', 'docx', 'txt', 'ppt', 'pptx', 'tiff', 'odt', 'ods', 'xls', 'xlsx'])) {
            return 'doc';
        } else {
            return 'file';
        }
    }
}

if (!function_exists('fileDestroy')) {
    /**
     * Delete file
     *
     * @param $path
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
            $image = \App\Models\File::find($source);
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
        Log::error(
            '(Date: ' . date('M d Y h:i:s A', time()) .
            ' - Error Message: ' . clean($message) .
            ' - Request Path: ' .
            "http://" . ((isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) ? $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : request()->getRequestUri())
        );
    }
}
