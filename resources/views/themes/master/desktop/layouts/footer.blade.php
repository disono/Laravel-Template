{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<div class="container mb-4">
    <footer>
        <div class="row mt-4">
            <div class="col-sm-12 col-md-4 col-lg-4">
                <nav class="nav flex-column">
                    <a class="nav-link" href="#">Link</a>
                    <a class="nav-link" href="#">Link</a>
                </nav>
            </div>

            <div class="col-sm-12 col-md-4 col-lg-4">
                <nav class="nav flex-column">
                    <a class="nav-link" href="#">Link</a>
                    <a class="nav-link" href="#">Link</a>
                </nav>
            </div>

            <div class="col-sm-12 col-md-4 col-lg-4">
                <nav class="nav flex-column">
                    <a class="nav-link" href="#">Link</a>
                    <a class="nav-link" href="#">Link</a>
                </nav>
            </div>
        </div>

        <p class="text-center mt-4">{{ __settings('title')->value}} &copy; {{date('Y') }}</p>
    </footer>
</div>