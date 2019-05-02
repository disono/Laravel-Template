{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

{{-- Inlince CSS for VueJS --}}
<style>
    [v-cloak] > * {
        display: none;
        text-align: center !important;
    }

    [v-cloak]::before {
        content: "Loading…";
        font-size: 22px;
        text-align: center !important;
        display: block;
        margin: 12em 0 !important;
    }
</style>