<x-app-layout>
    @php
        /** @var \Illuminate\Pagination\LengthAwarePaginator|\App\Models\File[] $files  */
    @endphp


    @section('title', trans('tasks.title'))

    @section('styles')
        @vite(['resources/vendor/jasny/jasny-bootstrap.min.css','resources/vendor/chosen/bootstrap-chosen.css','resources/vendor/switchery/switchery.css'])
    @endsection

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{trans('tasks.title')}}</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">{{trans('main.devices.home')}}</a>
                </li>

                <li class="active">
                    <strong>{{trans('tasks.title')}}</strong>
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
                        <h5>{{trans('files.sub_title')}}</h5>
                        <div class="ibox-tools">

                            <a data-toggle="modal" class="btn btn-primary" href="#modal-form"
                               data-href='{{route('task.create')}}'>
                                {{trans('main.actions.create')}} <i class="fa fa-plus" aria-hidden="true"></i>
                            </a>
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">

                        @include('_partials.paginator', ['paginator' => $tasks, 'paginatorOnly' => false, 'paginatorAppends' => ['extra' => 1], 'includeSearch' => false])

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover sortable">
                                <thead>
                                <tr>
                                    <th width="5%" class="{{ getSortingClass($sort, 'id', $asc) }}"><a
                                                href="{{ updateCurrentUrl(['sort' => 'id', 'asc' => !$asc]) }}">#</a>
                                    </th>
                                    <th class="{{ getSortingClass($sort, 'name', $asc) }}"><a
                                                href="{{ updateCurrentUrl(['sort' => 'title', 'asc' => !$asc]) }}">title</a>
                                    </th>
                                    <th class="{{ getSortingClass($sort, 'name', $asc) }}"><a
                                            href="{{ updateCurrentUrl(['sort' => 'title', 'asc' => !$asc]) }}">description</a>
                                    </th>
                                    <th class="{{ getSortingClass($sort, 'file_type', $asc) }}"><a
                                                href="{{ updateCurrentUrl(['sort' => 'assign_to', 'asc' => !$asc]) }}">{{ trans('assign_to') }}</a>
                                    </th>
                                    <th class="{{ getSortingClass($sort, 'default', $asc) }}"><a
                                                href="{{ updateCurrentUrl(['sort' => 'created_by', 'asc' => !$asc]) }}">{{ trans('admin') }}</a>
                                    </th>
                                    <th class="{{ getSortingClass($sort, 'default', $asc) }}"><a
                                            href="{{ updateCurrentUrl(['sort' => 'created_at', 'asc' => !$asc]) }}">{{ trans('created_at') }}</a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody><!-- Select2 -->

                                @foreach($tasks as $task)
                                    <tr class="gradeX">
                                        <td>{{ $task->id }}</td>
                                        <td>{{ $task->title }}</td>
                                        <td>{{ $task->description }}</td>
                                        <td>{{  $task->assignTo }}</td>
                                        <td>{{  $task->createdBy }}</td>
                                        <td>{{ $task->created_at->diffForHumans()}}</td>
                                    </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Assign_to</th>
                                    <th>created_by</th>
                                    <th>created_at</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
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
    @section('scripts')
        @vite(['resources/js/chosen.js','resources/vendor/jasny/jasny-bootstrap.min.js'])
        <script src="{{asset('js/plugins/switchery/switchery.js')}}" defer></script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                $('.form-filter').on('change', function (e) {
                    $(this).submit();
                });
            });
        </script>
    @endsection
</x-app-layout>
