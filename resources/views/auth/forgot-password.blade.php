<x-guest-layout>
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>
                <h1 class="logo-name"><br/></h1>
            </div>
            <h3>{{ __('login.forgot') }}</h3>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form class="m-t" action="{{ route('password.email') }}" method="post">
                {{ csrf_field() }}
                <!-- Email Address -->
                <div class="form-group">
                    <x-text-input id="email" class="block mt-1 w-full form-control"
                                  type="email" name="email" :value="old('email')"
                                  placeholder="Email"
                                  required autofocus autocomplete="username"/>
                    <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b mt-2"
                        name="login"> {{ __('Email Password Reset Link') }}</button>

                <p class="text-muted text-center">
                    <small>{{__('login.have_account')}}</small>
                </p>
                <a class="btn btn-sm btn-white btn-block" href="http://devicemanager.local/login">{{__('login.login')}}</a>
                <p class="m-t">
                    <small>{!! trans('login.copy')!!} &copy; 2024</small>
                </p>
            </form>
        </div>
    </div>


</x-guest-layout>
