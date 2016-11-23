# Laravel Project Template
is a starting blank template for Laravel Projects

# 3rd party Libraries
* **Intervention:** [http://image.intervention.io](http://image.intervention.io)
* **Agent:** [https://github.com/jenssegers/agent](https://github.com/jenssegers/agent)
* **Purifier:** [https://github.com/mewebstudio/Purifier](https://github.com/mewebstudio/Purifier)
* **QRCode:** [https://github.com/SimpleSoftwareIO/simple-qrcode](https://github.com/SimpleSoftwareIO/simple-qrcode)
* **HTMLMin:** [https://github.com/GrahamCampbell/Laravel-HTMLMin](https://github.com/GrahamCampbell/Laravel-HTMLMin)
* **Socialite:** [https://github.com/laravel/socialite](https://github.com/laravel/socialite)
* **jwt-auth:** [https://github.com/tymondesigns/jwt-auth](https://github.com/tymondesigns/jwt-auth)
* **Elephant.io:** [https://github.com/Wisembly/elephant.io](https://github.com/Wisembly/elephant.io)

# JavaScript Libraries
* **jQuery:** [https://jquery.com](https://jquery.com)
* **Animate.css:** [http://daneden.github.io/animate.css](http://daneden.github.io/animate.css)
* **Awesome Bootstrap Checkbox:** [https://github.com/flatlogic/awesome-bootstrap-checkbox](https://github.com/flatlogic/awesome-bootstrap-checkbox)
* **Bootstrap:** [http://getbootstrap.com](http://getbootstrap.com)
* **Bootstrap Select:** [http://silviomoreto.github.io/bootstrap-select](http://silviomoreto.github.io/bootstrap-select)
* **Font Awesome:** [https://fortawesome.github.io/Font-Awesome](https://fortawesome.github.io/Font-Awesome)
* **Ionicons:** [http://ionicons.com](http://ionicons.com)
* **Jquery Form:** [http://jquery.malsup.com/form](http://jquery.malsup.com/form)
* **MomentJS:** [http://momentjs.com](http://momentjs.com)
* **Pickadate:** [http://amsul.ca/pickadate.js](http://amsul.ca/pickadate.js)
* **Seiyria Bootstrap Slider:** [https://github.com/seiyria/bootstrap-slider](https://github.com/seiyria/bootstrap-slider)
* **Toastr:** [https://github.com/CodeSeven/toastr](https://github.com/CodeSeven/toastr)
* **Html5 QrCode:** [https://github.com/dwa012/html5-qrcode](https://github.com/dwa012/html5-qrcode)
* **Chart.js:** [http://www.chartjs.org](http://www.chartjs.org)
* **Angular:** [https://angularjs.org](https://angularjs.org)
* **Sweetalert:** [http://t4t5.github.io/sweetalert](http://t4t5.github.io/sweetalert)
* **loadCSS:** [https://github.com/filamentgroup/loadCSS](https://github.com/filamentgroup/loadCSS)
* **Bootstrap 3 Lightbox:** [https://github.com/ashleydw/lightbox](https://github.com/ashleydw/lightbox)

# How to install
```sh
Update your .local.env, .laradock.env and .production.env

composer install
php artisan migrate:refresh --seed

npm install --no-bin-links

bower install
bower update

gulp --production

uncomment the JWT initializer on ./routes/api.php
init_token_key();
```

# Laradock
LaraDock strives to make the development experience easier. It contains pre-packaged Docker Images that provides you a wonderful development environment without requiring you to install PHP, NGINX, MySQL, REDIS, and any other software on your local machine. [https://github.com/LaraDock/laradock](https://github.com/LaraDock/laradock) 
```sh
Usage Overview:

Let's see how easy it is to install NGINX, PHP, Composer, MySQL and Redis. Then run Laravel.

    1. Get LaraDock inside your Laravel project: 
        git clone https://github.com/LaraDock/laradock.git.
    2. Enter the laradock folder and run only these Containers: 
        docker-compose up -d nginx mysql redis
    3. Open your .env file and set DB_HOST to mysql and REDIS_HOST to redis.
    4. Open your browser and visit the localhost: http://localdock
```

# Credits
Laravel: [http://laravel.com](http://laravel.com) current version 5.3

# License
Laravel Project Template is licensed under the Apache License (ASL) license. For more information, see the LICENSE file in this repository.
