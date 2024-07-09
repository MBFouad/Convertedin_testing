

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> {{trans('login.title')}}</title>
{{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>--}}
    @vite(['resources/css/app.css','resources/scss/app.scss','resources/js/main.js'])
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>

<body class="gray-bg">

<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        <div>
            <h1 class="logo-name"><br/></h1>
        </div>
        <h3>{{__('login.welcome')}}</h3>

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
        <form class="m-t" action="{{ url('/login') }}" method="post">
            {{ csrf_field() }}
            <!-- Email Address -->
                    <div class="form-group">
                        <x-text-input id="email" class="block mt-1 w-full form-control"
                                      type="email" name="email" :value="old('email')"
                                      placeholder="Email"
                                      required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4 form-group">

                        <x-text-input id="password" class="block mt-1 w-full form-control"
                                        type="password"
                                        name="password"
                                        placeholder="Password"
                                        required autocomplete="current-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
            <button type="submit" class="btn btn-primary block full-width m-b mt-2"
                    name="login">{{trans('login.login')}}</button>

            <a href="{{url('/password/reset')}}">
                <small>{{trans('login.forgot')}}</small>
            </a>
        </form>
        <p class="m-t">
            <small>{{trans('login.copy')}} &copy; 2024</small>
        </p>
    </div>
</div>

<!-- Mainly scripts -->

@section('scripts')
@show

</body>

</html>








