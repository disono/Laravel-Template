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
* **Laravel-FCM:** [https://github.com/brozot/Laravel-FCM](https://github.com/brozot/Laravel-FCM)
* **setasign/fpdi-tcpdf:** [https://packagist.org/packages/setasign/fpdi-tcpdf](https://packagist.org/packages/setasign/fpdi-tcpdf)
* **Laravel Excel:** [https://github.com/Maatwebsite/Laravel-Excel](https://github.com/Maatwebsite/Laravel-Excel)
* **Free PDF Document Importer:** [https://www.setasign.com/products/fpdi/about](https://www.setasign.com/products/fpdi/about)
* **DOMPDF Wrapper for Laravel 5:** [https://github.com/barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf)
* **CORS Middleware for Laravel 5:** [https://github.com/barryvdh/laravel-cors](https://github.com/barryvdh/laravel-cors)

# JavaScript Libraries
* **jQuery:** [https://jquery.com](https://jquery.com)
* **VueJS:** [https://vuejs.org](https://vuejs.org)
* **Animate.css:** [http://daneden.github.io/animate.css](http://daneden.github.io/animate.css)
* **Bootstrap:** [http://getbootstrap.com](http://getbootstrap.com)
* **Font Awesome:** [https://fortawesome.github.io/Font-Awesome](https://fortawesome.github.io/Font-Awesome)
* **MomentJS:** [http://momentjs.com](http://momentjs.com)
* **Pickadate:** [http://amsul.ca/pickadate.js](http://amsul.ca/pickadate.js)
* **Toastr:** [https://github.com/CodeSeven/toastr](https://github.com/CodeSeven/toastr)
* **Chart.js:** [http://www.chartjs.org](http://www.chartjs.org)
* **Sweetalert:** [http://t4t5.github.io/sweetalert](http://t4t5.github.io/sweetalert)
* **loadCSS:** [https://github.com/filamentgroup/loadCSS](https://github.com/filamentgroup/loadCSS)

# How to install
```sh
1.) Create .env content must be (local, production and laradock), Update your .local.env, .laradock.env and .production.env

2.) Provide some Laravel permissions
    sudo chmod -R 755 storage bootstrap/
    sudo chmod -R 755 storage storage/

4.) composer install
5.) php artisan migrate:refresh --seed

6.) npm install --no-bin-links

7.) update all css and javascript
    // run all Mix tasks and minify output...
    // details @ https://laravel.com/docs/5.5/mix
    npm run production

8.) for real-time message (Windows)
    1. cd cloud_messaging
    2. npm install
    3. run.bat
    4. update your _socket_uri @ /public/assets/js/lib/socket.js

    for real-time messaging (Unix and Mac)
    1. cd cloud_messaging
    2. npm install
    3. mongod --dbpath ./storage/database
    4. node index.js
    5. update your _socket_uri @ /public/assets/js/lib/socket.js

9.) Facebook auth follow this guide https://github.com/laravel/socialite

10.) Configure OPcache, useful commands (https://github.com/appstract/laravel-opcache)
    - Make sure your APP_URL is set correctly in .env.
    - Learn more about OPcache (https://medium.com/appstract/make-your-laravel-app-fly-with-php-opcache-9948db2a5f93)
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

# Added Features
```sh
1. CMS (Content Management System)
2. Events
3. Mobile API
4. PDF support
```

# Javascript methods
```
Dialog
    this.dialogs('view-name', function (m) {
        // data
        m.data = {
            _data: new Date()
        };
        
        // confirm and dismiss buttons
        m.buttons();
    }, function (r) {
        // confirm callback
        console.log(r._data);
    }, function (r) {
        // dismiss callback
        console.log(r._data);
    });
```

# Additional Helper Functions
```sh
random_first_names
random_last_names
random_middle_names
list_defined_functions
is_selected
is_checked
array_search_value
has_input
generate_qr
is_img_data_base64
app_title
rand_token
rand_numbers
str_random_db
authenticated_id
get_request_value
request_value
request_options
number_shorten
html_app_cache
access_routes
is_percent
order_status
transaction_status
payment_types
order_types
db_filter_id
paginate
exclude_slug
success_json_response
failed_json_response
download_image
get_ip_address
get_user_agent
node_connector
socket_emit
fcm_send
fcm_topic
delete_file
filename_creator
error_logger
get_image
encode_base64_image
upload_any_file
upload_image
create_folder
upload_image_only
money
app_settings
app_header
config_per_page
config_file_size
config_img_quality
config_min_age
config_max_age
sql_date
sql_time
human_date
human_time
date_formatting
expired_at
count_years
count_hours
theme
current_theme
admin_view
url_ext
url_title
profile_url
active_url
me
authorize_me
authorize_route
api_auth
api_auth_jwt
init_token_key
resource_authorize
send_sms
dpf_modify
dpf_blade
```

# Credits
Laravel: [http://laravel.com](http://laravel.com) current version 5.5 (Latest Version)

# License
Laravel Project Template is licensed under the Apache License (ASL) license. For more information, see the LICENSE file in this repository.
