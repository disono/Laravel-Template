jQ(document).ready(function () {
    jQ("#menu-toggle").click(function(e) {
        e.preventDefault();
        jQ("#wrapper").toggleClass("toggled");
    });
});