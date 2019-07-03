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
                @includeTheme('user.settings.menu')
            </div>

            <div class="col-sm-12 col-lg-9">
                <a class="btn btn-primary mb-3" href="{{ route('user.setting.phone.create') }}"
                   role="button"><i class="fas fa-plus"></i> Add a New Phone Number</a>

                @if(count($phones))
                    <div class="row">
                        @foreach($phones as $row)
                            <div class="col-6" data-parent-list="cart_list_address" id="parent_card_{{ $row->id }}">
                                <div class="card m-0 mb-3 cart_list_address shadow-sm rounded bg-white border-0">
                                    <div class="card-body">
                                        <p class="card-text">
                                            {{ $row->phone }}
                                        </p>

                                        @if(__settings('phoneVerification')->value === 'enabled')
                                            @if($row->is_verified)
                                                <p class="text-success"><i class="fas fa-check"></i> Verified</p>
                                            @else
                                                <p class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                    Unverified</p>
                                            @endif
                                        @endif

                                        <a href="{{ url('user/setting/phone/edit/' . $row->id) }}"
                                           class="card-link">Edit</a>
                                        <a href="{{ url('user/setting/phone/destroy/' . $row->id) }}"
                                           class="card-link text-danger"
                                           id="parent_card_del_{{ $row->id }}"
                                           v-on:click.prevent="onDeleteResource($event, '#parent_card_{{ $row->id }}')">Delete</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{ $phones->appends($request->all())->render() }}
                @else
                    <div class="p-3 shadow-sm rounded bg-white border-0">
                        <h3 class="text-center"><i class="far fa-frown"></i> No Phone Number Added.</h3>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection