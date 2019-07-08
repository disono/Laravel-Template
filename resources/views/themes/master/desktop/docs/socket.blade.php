{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<h4>Socket IO (Client)</h4>
<hr>

<div class="custom-controls-stacked d-block my-3">
    <div class="bg-light p-3 mt-3 mb-3 rounded-lg">
        <code>
            @php
                echo nl2br(htmlspecialchars("let socket = io('http://localhost:4000', {
                        transportOptions: {
                            polling: {
                                extraHeaders: {
                                    'source': 'client',
                                    'app-name': 'appName'
                                    't-key': '',
                                    'uid': ''
                                }
                            }
                        }
                    });

                    socket.on('connect', function () {
                        console.log('onConnect');
                    });

                    socket.on('disconnect', function () {
                        console.log('onDisconnect');
                    });

                    socket.on('appName_to', (msg) => {
                        console.log(msg);
                    });
                "))
            @endphp
        </code>
    </div>
</div>