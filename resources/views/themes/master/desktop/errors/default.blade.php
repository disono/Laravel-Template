{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends(currentTheme() . 'layouts.master')

@section('content')
    <div class="container text-center">
        <div class="row p-3 rounded shadow-sm bg-white">
            <div class="col-md-5 mr-auto ml-auto">
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Oops Error Occurred!</h4>
                    <p>{{ $message }}</p>

                    @if(isset($contact))
                        <p class="mb-0">{{ $contact }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection