{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends(currentTheme() . 'layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-lg-3">
                @include(currentTheme() . 'user.settings.menu')
            </div>

            <div class="col-sm-12 col-lg-9  p-3 shadow-sm rounded bg-white">
                <a class="btn btn-primary mb-3" href="{{ url('user/setting/address/create') }}"
                   role="button"><i class="fas fa-plus"></i> Add a New Address</a>

                @if(count($addresses))
                    <div class="row">
                        @foreach($addresses as $row)
                            <div class="col-6">
                                <div class="card m-0 mb-3 cart_list_address" data-parent-list="cart_list_address"
                                     id="parent_card_{{ $row->id }}">
                                    <div class="card-body">
                                        <p class="card-text m-0">
                                            {{ $row->address }}
                                        </p>
                                        <p class="card-text m-0">
                                            {{ $row->postal_code }}
                                        </p>
                                        <p class="card-text m-0">
                                            {{ $row->country_name }}
                                        </p>
                                        <p class="card-text m-0 mb-3">
                                            {{ $row->city_name }}
                                        </p>

                                        <a href="{{ url('user/setting/addresses/edit/' . $row->id) }}"
                                           class="card-link">Edit</a>
                                        <a href="{{ url('user/setting/addresses/destroy/' . $row->id) }}"
                                           class="card-link text-danger"
                                           id="parent_card_del_{{ $row->id }}"
                                           v-on:click.prevent="onDeleteResource($event, '#parent_card_{{ $row->id }}')">Delete</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{ $addresses->appends($request->all())->render() }}
                @else
                    <h3 class="text-center"><i class="far fa-frown"></i> No Address Found.</h3>
                @endif
            </div>
        </div>
    </div>
@endsection