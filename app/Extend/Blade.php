<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Extend;

use Illuminate\Support\Facades\Blade;

class ExtendBlade
{
    public function extend()
    {
        Blade::directive('includeTheme', function ($arguments) {
            $params = $this->getArguments($arguments);
            $path = currentTheme() . str_replace("'", '', $params[0]);
            $data = $params[1] ?? '([])';
            return "<?php echo \$__env->make('{$path}', array_except(get_defined_vars(), ['__data', '__path']))->with($data)->render(); ?>";
        });

        // This register the whole trio: auth, else and endauth.
        Blade::if('auth', function () {
            return auth()->check();
        });

        // This register the whole trio: authorize, else and endauthorize.
        Blade::if('authorize', function ($roles) {
            return isAuthorize($roles, true);
        });

        // This register the whole trio: auth_route, else and endauth_route.
        Blade::if('auth_route', function () {
            return authorizeRoute();
        });
    }

    private function getArguments($argumentString)
    {
        return explode(',', $argumentString);
    }
}