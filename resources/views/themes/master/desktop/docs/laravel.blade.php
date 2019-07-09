<h4>Custom Blade Directives</h4>
<hr>

<div class="bg-light p-3 mt-3 mb-3 rounded-lg">
    <code>
        <p class="font-weight-bold p-0 m-0"># check if user is authenticated </p>
        <p>@@auth, @@else and @@endauth</p>

        <p class="font-weight-bold p-0 m-0"># include theme</p>
        <p class="font-weight-bold p-0 m-0"># similar to @@include(currentTheme() . 'path')</p>
        <p>@@includeTheme('path', ['data' => 'val'])</p>

        <p class="font-weight-bold p-0 m-0"># check role for access</p>
        <p>@@authorize('role|role|role'), @@else and @@endauthorize</p>

        <p class="font-weight-bold p-0 m-0"># check if user has access to current route</p>
        <p>@@auth_route, @@else and @@endauth_route</p>

        <p class="font-weight-bold p-0 m-0"># create a new model</p>
        <p class="p-0 m-0">$ php artisan make:model Models/modelName -m </p>
        <p class="p-0 m-0">Create a new file: /Models/Vendor/Facades modelName.php</p>
        <p class="p-0 m-0">Add your facades to /Models/Vendor/Facades/ModelServiceProvider.php</p>
    </code>
</div>