<x-app-layout>

@php
/** @var \App\Models\User[] $admins */
@endphp


@section('title', trans('administrators.title'))

{{--@section('content')--}}
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{trans('administrators.title')}}</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/home')}}">{{trans('main.devices.home')}}</a>
                </li>

                <li class="active">
                    <strong>{{trans('administrators.title')}}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        @if (session('status'))
            <div class="alert alert-success alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                {{ session('status') }}.
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{trans('administrators.sub-title')}}</h5>
                        <div class="ibox-tools">


                            <a data-toggle="modal" class="btn btn-primary" href="#modal-form"
                               data-href='{{ route('administrators.create') }}'>
                                {{ trans('main.actions.create') }} <i class="fa fa-plus" aria-hidden="true"></i>
                            </a>
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">

                        @include('_partials.paginator', ['paginator' => $admins, 'paginatorOnly' => false, 'includeSearch' => true])

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover sortable">
                                <thead>
                                <tr>
                                    <th width="5%" class="{{ getSortingClass($sort, 'id', $asc) }}"><a href="{{ updateCurrentUrl(['sort' => 'id', 'asc' => !$asc]) }}">#</a></th>
                                    <th class="{{ getSortingClass($sort, 'name', $asc) }}"><a href="{{ updateCurrentUrl(['sort' => 'name', 'asc' => !$asc]) }}">{{ trans('administrators.titles.user-name') }}</a></th>
                                    <th class="{{ getSortingClass($sort, 'type', $asc) }}"><a href="{{ updateCurrentUrl(['sort' => 'type', 'asc' => !$asc]) }}">{{ trans('administrators.titles.user-type') }}</a></th>
                                    <th class="{{ getSortingClass($sort, 'language', $asc) }}"><a href="{{ updateCurrentUrl(['sort' => 'language', 'asc' => !$asc]) }}">{{ trans('main.titles.language') }}</a></th>
                                    <th>{{ trans('main.titles.additional') }}</th>
                                    <th class="{{ getSortingClass($sort, 'status', $asc) }}"><a href="{{ updateCurrentUrl(['sort' => 'status', 'asc' => !$asc]) }}">{{ trans('administrators.titles.status') }}</a></th>
                                    <th width="15%" class="no-sort">{{trans('main.titles.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($admins as $admin)
                                    <tr class="gradeX">
                                        <td>{{ $admin->id }}</td>
                                        <td>{{ $admin->name ?? '-' }}</td>
                                        <td>{{ $admin->user_type_translated }}</td>
                                        <td>{{ $admin->language->name ?? '' }}</td>
                                        <td>
                                            <div><strong>Distributions:</strong></div>
                                            @if(!$admin->distributions->count())
                                                None
                                            @elseif($admin->distributions->count() === $allDistributionsAmount)
                                                All
                                            @else
                                                <div style="max-height: 100px; overflow-y: auto; overflow-x: hidden">
                                                    @foreach($admin->distributions as $distribution)
                                                        <div>
                                                            @if($distribution->parentDistribution)
                                                                {{ $distribution->parentDistribution->name }}:
                                                            @endif
                                                            {{ $distribution->name }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <div>&nbsp;</div>


                                            @if ($admin->isTad() || $admin->isHotLine())
                                                <div><strong>{{ trans('main.titles.can_generate_key') }}:</strong></div>
                                                <div>{{ $admin->can_generate_key ? 'Yes' : 'No' }}</div>
                                                <div>&nbsp;</div>
                                            @endif

                                            @if ($admin->isHotLine())
                                                <div><strong>{{ trans('main.titles.can_generate_code') }}:</strong></div>
                                                <div>{{ $admin->can_generate_code ? 'Yes' : 'No' }}</div>
                                                <div>&nbsp;</div>
                                            @endif

                                            <div><strong>{{ trans('main.titles.can_see_device_map') }}:</strong></div>
                                            <div>{{ $admin->can_see_device_map ? 'Yes' : 'No' }}</div>
                                            <div>&nbsp;</div>

                                            <div><strong>{{ trans('main.titles.can_see_service_manuals') }}:</strong></div>
                                            <div>{{ $admin->can_see_service_manuals ? 'Yes' : 'No' }}</div>
                                            <div>&nbsp;</div>

                                            <div><strong>{{ trans('main.titles.can_see_spare_parts') }}:</strong></div>
                                            <div>{{ $admin->can_see_spare_parts ? 'Yes' : 'No' }}</div>
                                            <div>&nbsp;</div>

                                            <div><strong>{{ trans('main.titles.can_see_tech_data') }}:</strong></div>
                                            <div>{{ $admin->can_see_tech_data ? 'Yes' : 'No' }}</div>
                                            <div>&nbsp;</div>

                                            <div><strong>{{ trans('main.titles.can_receive_emails') }}:</strong></div>
                                            <div>{{ $admin->can_receive_emails ? 'Yes' : 'No' }}</div>
                                            <div>&nbsp;</div>

                                            <div><strong>{{ trans('main.titles.can_receive_reg_emails') }}:</strong></div>
                                            <div>{{ $admin->can_receive_reg_emails ? 'Yes' : 'No' }}</div>
                                            <div>&nbsp;</div>

                                            @if ($admin->isTad())
                                                <div><strong>{{ trans('main.titles.can_register_units_to_own_account') }}</strong></div>
                                                <div>{{ $admin->can_register_units_to_own_account ? 'Yes' : 'No' }}</div>
                                                <div>&nbsp;</div>
                                                <div><strong>{{ trans('main.labels.max_amount_units_own_account') }}</strong></div>
                                                <div>{{ $admin->max_amount_units_own_account }}</div>
                                                <div>&nbsp;</div>
                                                <div><strong>{{ trans('main.titles.can_move_own_units_to_customer') }}</strong></div>
                                                <div>{{ $admin->can_move_own_units_to_customer ? 'Yes' : 'No' }}</div>
                                                <div>&nbsp;</div>
                                            @endif

                                            @if ($admin->isHotLine())
                                                <div><strong>{{ trans('main.titles.can_move_units_to_customer') }}</strong></div>
                                                <div>{{ $admin->can_move_units_to_customer ? 'Yes' : 'No' }}</div>
                                                <div>&nbsp;</div>
                                            @endif

                                        </td>
                                        <td>{{$admin->status_translated}}</td>

                                        <td class="center">
                                            <a data-toggle="modal" class="btn btn-primary" href="#modal-form"
                                               data-href='{{route('administrators.show',$admin->id)}}'
                                               data-tooltip="tooltip" title="{{trans('administrators.showUserHint')}}">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                            <a data-toggle="modal" class="btn btn-primary" href="#modal-form"
                                               data-href='{{route('administrators.edit', $admin->id)}}'
                                               data-tooltip="tooltip" title="{{trans('administrators.edit_customer_hint')}}">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                            </a>
                                            <a data-popout="true" data-token='{{ csrf_token()}}'
                                               data-hreff='{{route('administrators.destroy',$admin->id)}}'
                                               data-url='{{route('administrators.index')}}'
                                               data-id='{{$admin->id}}'
                                               class="btn btn-danger red-mint delete-ajax"
                                               data-toggle="confirmation"
                                               data-title="{{trans('main.labels.delete_confirmation_message') }}"
                                               data-content="This might be dangerous"
                                               data-placement="top"
                                               data-btn-ok-label="{{trans('main.labels.delete')}}"
                                               data-btn-ok-icon="glyphicon glyphicon-share-alt"
                                               data-btn-ok-class="btn-success"
                                               data-btn-cancel-label=" {{ trans('main.labels.cancel')}}"
                                               data-btn-cancel-icon="glyphicon glyphicon-ban-circle"
                                               data-btn-cancel-class="btn-danger"
                                               data-tooltip="tooltip"
                                               title="{{trans('administrators.removeHint')}}"
                                            >
                                                <i class="fa fa-trash-o"></i></a>
                                            @if ($admin->hasRole('Admin') && \Illuminate\Support\Facades\Auth::user()->hasRole('Admin'))
                                                @if ($admin->isActive())
                                                    <a data-popout="true" data-token='{{ csrf_token() }}'
                                                       class="btn btn-danger red-mint delete-ajax"
                                                       data-hreff='{{route('administrators.deactivate', $admin->id)}}'
                                                       data-url='{{route('administrators.index')}}'
                                                       data-id='{{$admin->id}}'
                                                       data-toggle="confirmation"
                                                       data-original-title="{{trans('main.labels.deactivate_confirmation_message')}}"
                                                       data-placement="left"
                                                       data-btn-ok-label="{{trans('main.labels.deactivate')}}"
                                                       data-btn-cancel-label="{{trans('main.labels.cancel')}}"
                                                       data-tooltip="tooltip"
                                                       title="{{trans('main.labels.deactivate')}}">
                                                        <i class="fa fa-power-off" aria-hidden="true"></i>
                                                    </a>
                                                @else
                                                    <a data-popout="true" data-token='{{ csrf_token() }}'
                                                       class="btn btn-success red-mint delete-ajax"
                                                       data-hreff='{{route('administrators.activate', $admin->id)}}'
                                                       data-url='{{route('administrators.index')}}'
                                                       data-toggle="confirmation"
                                                       data-original-title="{{trans('main.labels.activate_confirmation_message')}}"
                                                       data-placement="left"
                                                       data-btn-ok-label="{{trans('main.labels.activate')}}"
                                                       data-btn-cancel-label="{{trans('main.labels.cancel')}}"
                                                       data-tooltip="tooltip"
                                                       title="{{trans('main.labels.activate')}}">
                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach


                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>{{trans('administrators.titles.user-name')}}</th>
                                    <th>{{trans('administrators.titles.user-type')}}</th>
                                    <th>{{trans('main.titles.language')}}</th>
                                    <th>{{trans('main.titles.actions')}}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        @include('_partials.paginator', [
                                                    'paginator'     => $admins,
                                                    'paginatorOnly' => false,
                                                    'includeSearch' => true,
                                                    ]
                                                )
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-form" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="sk-spinner sk-spinner-three-bounce">
                        <div class="sk-bounce1"></div>
                        <div class="sk-bounce2"></div>
                        <div class="sk-bounce3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--@endsection--}}
</x-app-layout>
