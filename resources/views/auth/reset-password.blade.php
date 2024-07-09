<x-guest-layout>



    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>
                <h1 class="logo-name"><br/></h1>
            </div>
            <h3>{{ __('login.reset') }}</h3>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="m-t" action="{{ route('password.store') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <!-- Email Address -->
                <div class="form-group">
                    <x-text-input id="email" class="block mt-1 w-full form-control"
                                  type="email" name="email" :value="old('email')"
                                  placeholder="Email"
                                  required autofocus autocomplete="username"/>
                    <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                </div>
                <div class="mt-4 form-group">
                    <x-text-input id="password" class="block mt-1 w-full form-control"     placeholder="{{__('register.password')}}" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div class="mt-4 form-group">
                    <x-text-input id="password_confirmation" class="block mt-1 w-full form-control"
                                  type="password"
                                  name="password_confirmation"     placeholder="{{__('register.password-confirmation')}}" required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b mt-2"
                        name="login">{{ __('login.reset') }}</button>
            </form>
        </div>
    </div>

</x-guest-layout>
