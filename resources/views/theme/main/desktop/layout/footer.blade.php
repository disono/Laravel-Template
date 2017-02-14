{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}

<div class="container">
    <footer class="footer">
        {{-- subscriber form --}}
        @if(app_settings('subscriber_form')->value == 'enabled' && !auth()->check() && !request()->is('login') && !request()->is('register'))
            @include(current_theme() . 'page.subscriber')
        @endif

        <hr>
        <p>{{app_settings('title')->value}} &copy; {{date('Y')}}</p>
    </footer>
</div>