<x-app-layout>

    @section('title', trans('administrators.labels.change_password'))

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{ trans('administrators.labels.change_password') }}</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('home') }}">{{trans('main.titles.home')}}</a>
                </li>

                <li class="active">
                    <strong>{{ trans('administrators.labels.change_password') }}</strong>
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

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ trans('administrators.labels.change_password') }}</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        {{ html()->modelForm(\Illuminate\Support\Facades\Auth::user(), 'patch', route('profile.change-password.store'))->attributes(['id'=>'formTest', 'class'=>'form-horizontal'])->open() }}
                        <div class="form-group  {{($errors->has('old_password')) ? 'has-error' : ''}}">
                            <label class="col-lg-2 control-label">
                                {{trans('customer.labels.old-password')}}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-10">
                                {{  html()->password('old_password')->attributes( ['id'=>'old_password', 'class'=>'form-control', 'placeholder'=>trans('administrators.labels.old_password') ]) }}

                                @if ($errors->has('old_password'))
                                    <span class="help-block m-b-none">{{ $errors->first('old_password') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group  {{ $errors->has('password') ? 'has-error' : '' }}">
                            <label class="col-lg-2 control-label">
                                {{ trans('register.password') }}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-10">
                                {{  html()->password('password')->attributes(['id' => 'password', 'class' => 'form-control', 'placeholder' => trans('register.password') ]) }}

                                @if ($errors->has('password'))
                                    <span class="help-block m-b-none">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="password_confirmation">
                                {{ trans('register.password-confirmation') }}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-10">
                                {{  html()->password('password_confirmation')->attributes(['id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => trans('register.password-confirmation') ]) }}

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button class="btn btn-primary block  m-b"
                                        type="submit">{{ trans('administrators.labels.update') }}</button>
                            </div>
                        </div>
                        {{ html()->closeModelForm() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
