jQ(document).ready(function () {
    var shared_images = jQ('#images_container').html();

    jQ('#addImage').off().on('click', function (e) {
        e.preventDefault();

        jQ('#images').append(shared_images);
    });
});