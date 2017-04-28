{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}

<div class="container">
    <footer class="footer">
        {{-- subscriber form --}}
        @if(app_settings('subscriber_form')->value == 'enabled' && !auth()->check() && !request()->is('login') && !request()->is('register') && !request()->is('password/recover'))
            @include(current_theme() . 'page.subscriber')
        @endif

        <div class="has-header">{{app_settings('title')->value}} &copy; {{date('Y')}}</div>
    </footer>
</div>