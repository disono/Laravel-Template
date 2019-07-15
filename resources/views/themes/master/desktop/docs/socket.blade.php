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
        <pre class="p-0 m-0"><code>@php
                    echo trim(htmlspecialchars("let socket = io('http://localhost:4000', {
    transportOptions: {
        polling: {
            extraHeaders: {
                'authorization': 'Bearer your-token',
                'source': 'client',
                'app-name': 'appName'
                'tkey': '',
                'uid': ''
            }
        }
    }
});

socket.on('connect', function() {
    console.log('onConnect');
});

socket.on('disconnect', function() {
    console.log('onDisconnect');
});

socket.on('appName_to', (msg) => {
    console.log(msg);
});

// client subscription
// format: appName_token_nameOfYourSubscription
WBSocketIO.builder().then(self => {
    self.subscriptions([
        // sample client
        {
            name: 'client',
            callback: data => {
                console.log(data);
            }
        },

        // sample chat
        {
            name: 'chat',
            callback: data => {
                console.log(data);
            }
        },

        // me
        {
            name: null,
            callback: data => {
                console.log(data);
            }
        }
    ]);

    self.publish('to', {
        data: 'My Data Here'
    });
});"))@endphp
        </code></pre>
    </div>
</div>