{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<footer class="row mt-5 mb-5">
    <div class="col text-center">
            <span class="text-muted">Powered by <a
                        href="{{ __settings('appUrl')->value }}">{{ __settings('title')->value }}</a> &copy; {{ date('Y') }}</span>
    </div>
</footer>