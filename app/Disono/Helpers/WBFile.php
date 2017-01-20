<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
 */
namespace App\Disono\Helpers;

use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class WBFile
{
    private static $place_holder = 'assets/img/placeholder/';

    /**
     * Upload file
     *
     * @param $file
     * @param $destinationPath
     * @param null $old_file
     * @return string
     */
    public static function uploadFile($file, $destinationPath, $old_file = null)
    {
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $upload_filename = filename_creator() . '.' . $extension;
            $file->move($destinationPath, $upload_filename);

            // delete old file
            if ($old_file) {
                self::delete($old_file);
            }

            return $upload_filename;
        }

        return null;
    }

    /**
     * Delete file
     *
     * @param $path
     * @return bool
     */
    public static function delete($path)
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

    /**
     * Upload image
     *
     * @param $file
     * @param array $image_options
     * @param null $old_file
     * @param string $destinationPath
     * @param bool $nameOnly
     * @return string
     */
    public static function uploadImage($file, $image_options = [], $old_file = null, $destinationPath = 'private/img', $nameOnly = false)
    {
        if ($file) {
            $uploaded = true;

            if (is_array($file)) {
                // multiple files
                foreach ($file as $value) {
                    self::_processUploadedImage($value, $image_options, $destinationPath, $nameOnly);
                }
            } else {
                $uploaded = self::_processUploadedImage($file, $image_options, $destinationPath, $nameOnly);
            }

            // delete old file
            if ($old_file && $uploaded) {
                if (is_numeric($old_file) && $old_file > 0) {
                    // get the old image to delete
                    $query_image = \App\Models\Image::find($old_file);

                    if ($query_image) {
                        $is_deleted = delete_file($destinationPath . '/' . $query_image->filename);

                        if ($is_deleted) {
                            $query_image->delete();
                        }
                    }
                } else {
                    delete_file($old_file);
                }
            }

            return $uploaded;
        }

        return 0;
    }

    /**
     * File name creator
     *
     * @return string
     */
    public static function filenameCreator()
    {
        return str_random() . '-' . time();
    }

    /**
     * Log errors
     *
     * @param $message
     */
    public static function errorLogger($message)
    {
        Log::error('(Date/Time: ' . date('M d Y h:i:s A', time()) .
            ' - Error Message: ' . clean($message) . ') - Request Path: ' .
            "http://" . ((isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) ?
                $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : request()->getRequestUri()) . "\n");
    }

    /**
     * Get image
     *
     * @param $source
     * @param null $type
     * @param bool $path_only
     * @return string
     */
    public static function getImg($source, $type = null, $path_only = false)
    {
        $image = null;
        $path = null;
        if (is_numeric($source)) {
            $image = \App\Models\Image::find($source);
        }

        $filename = null;
        if ($image) {
            $filename = $image->filename;
        } else {
            $filename = $source;
        }

        // complete path for image
        $complete_path = 'private/img/' . $filename;

        // check if folder exists
        if (!file_exists('private/img')) {
            if (!file_exists('private')) {
                mkdir('private');
            }

            // create the folder
            self::createFolder('private/img');
        }

        if (file_exists($complete_path) && $filename != null) {
            $path = $complete_path;
        } else {
            if ($type === 'avatar') {
                $path = self::$place_holder . 'no-avatar.png';
            } else {
                $path = self::$place_holder . 'no-image.png';
            }
        }

        return ($path_only) ? $path : url($path);
    }

    /**
     * Create base64 image (Local file only)
     *
     * @param $filename
     * @param $filetype
     * @return null|string
     */
    public static function encodeBASE64Image($filename, $filetype = 'png')
    {
        if ($filename) {
            $imgbinary = fread(fopen($filename, "r"), filesize($filename));
            return 'data:image/' . $filetype . ';base64,' . base64_encode($imgbinary);
        }

        return null;
    }

    /**
     * Create folder
     *
     * @param $path
     * @return bool
     */
    public static function createFolder($path)
    {
        // check if folder exists
        if (!file_exists($path)) {
            mkdir($path);
        }

        return true;
    }

    /**
     * Process uploaded image
     *
     * @param $file
     * @param array $image_options
     * @param $destinationPath
     * @param bool $nameOnly
     * @return string
     */
    private static function _processUploadedImage($file, $image_options = [], $destinationPath = 'private/img', $nameOnly = false)
    {
        if (!$file) {
            return null;
        }

        $extension = $file->getClientOriginalExtension();
        $upload_filename = filename_creator() . '.' . $extension;
        $file->move($destinationPath, $upload_filename);

        // manipulate image
        $upload_file = Image::make($destinationPath . '/' . $upload_filename);

        if ($upload_file) {
            // crop
            if (isset($image_options['crop_width']) && isset($image_options['crop_height'])) {
                $upload_file->crop((int)$image_options['crop_width'], (int)$image_options['crop_height']);
            } else if (isset($image_options['crop_auto'])) {
                $height = $upload_file->height() * 0.85;
                $width = $height;
                $upload_file->crop((int)$width, (int)$height);
            }

            // resize
            if (isset($image_options['width']) && isset($image_options['height'])) {
                $upload_file->resize((int)$image_options['width'], (int)$image_options['height']);
            }

            // resize only the height of the image
            if (!isset($image_options['width']) && isset($image_options['height'])) {
                $upload_file->resize(null, (int)$image_options['height'], function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            // resize only the width of the image
            if (isset($image_options['width']) && !isset($image_options['height'])) {
                $upload_file->resize((int)$image_options['width'], null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            // save
            $upload_file->save(null, config_img_quality($upload_file->filesize()));
        }

        // return filename
        if ($nameOnly) {
            return $upload_filename;
        }

        // insert image to database
        return \App\Models\Image::insertGetId([
            'user_id' => ((isset($image_options['user_id'])) ? $image_options['user_id'] : 0),
            'source_id' => ((isset($image_options['source_id'])) ? $image_options['source_id'] : 0),

            'title' => ((isset($image_options['title'])) ? $image_options['title'] : null),
            'description' => ((isset($image_options['description'])) ? $image_options['description'] : null),

            'filename' => $upload_filename,
            'type' => ((isset($image_options['type'])) ? $image_options['type'] : null),

            'created_at' => sql_date()
        ]);
    }
}