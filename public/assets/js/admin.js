/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

jQ(document).ready(function () {
    jQ("#menu-toggle").click(function (e) {
        e.preventDefault();
        jQ("#wrapper").toggleClass("toggled");
    });
});