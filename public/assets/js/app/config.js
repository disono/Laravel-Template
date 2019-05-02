/**
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
 */

let jQ = jQuery.noConflict();

let WBAppConfig = function () {
    return {
        httpHeaders: function () {
            return {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': jQ('meta[name="_token"]').attr('content')
            };
        },

        httpUploadHeaders: function () {
            return {
                'Content-Type': 'multipart/form-data',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': jQ('meta[name="_token"]').attr('content')
            };
        }
    };
};