<x-app-layout>
    @php
        /** @var \Illuminate\Pagination\LengthAwarePaginator|\App\Models\File[] $files  */
    @endphp


    @section('title', trans('statistics'))

    @section('styles')
        @vite(['resources/vendor/jasny/jasny-bootstrap.min.css','resources/vendor/chosen/bootstrap-chosen.css','resources/vendor/switchery/switchery.css'])
    @endsection

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Statistics</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">{{trans('main.devices.home')}}</a>
                </li>

                <li class="active">
                    <strong>Statistics</strong>
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
                    <div class="ibox-content">


                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover sortable">
                                <thead>
                                <tr>
                                    <th> User</th>
                                    <th> Count of Tasks</th>
                                </tr>
                                </thead>
                                <tbody><!-- Select2 -->

                                @foreach($topUsers as $user)
                                    <tr class="gradeX">
                                        <td>{{ $user->assignTo->name }}</td>
                                        <td>{{ $user->tasks_count }}</td>
                                    </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th> User</th>
                                    <th> Count of Tasks</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
