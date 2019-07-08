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
                <div class="p-3 shadow-sm rounded bg-white border-0">
                    @include('vendor.app.toolbar', ['toolbarHasDel' => true])

                    <div class="table-responsive-sm">
                        <table class="table table-bordered">
                            <thead class="table-borderless bg-light">
                            <tr>
                                @if(__settings('allowDelPageReport')->value === 'enabled')
                                    {!! thDelete() !!}
                                @endif

                                <th scope="col">Ticket #</th>
                                <th scope="col">
                                    Reason
                                </th>
                                <th scope="col">
                                    Last Reply
                                </th>
                                <th scope="col">
                                    Date
                                </th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($reports as $row)
                                <tr id="parent_tr_{{$row->id}}">
                                    @if(__settings('allowDelPageReport')->value === 'enabled')
                                        {!! tdDelete($row->id) !!}
                                    @endif

                                    <td>{{ $row->id }}</td>
                                    <td>
                                        {{ $row->page_report_reason_name }}
                                    </td>
                                    <td>
                                        {{ str_limit($row->last_reply, 42) }}
                                    </td>
                                    <td>
                                        {{ $row->formatted_created_at }}
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-light btn-sm dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>

                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="{{ url('page-report/show/' . $row->id )}}">View
                                                    Conversation</a>

                                                @if(__settings('allowDelPageReport')->value === 'enabled')
                                                    <div class="dropdown-divider"></div>

                                                    <a class="dropdown-item"
                                                       href="{{ url('page-report/destroy/' . $row->id) }}"
                                                       id="parent_tr_del_{{ $row->id }}"
                                                       v-on:click.prevent="onDeleteResource($event, '#parent_tr_{{ $row->id }}')">Delete</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(!count($reports))
                        <h1 class="text-center"><i class="far fa-frown"></i> No Reports.</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection