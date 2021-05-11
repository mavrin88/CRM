@extends('layouts.app_auth')
@section('content')

    <main class="container">
        <div class="row">

            @if(session('message'))
                <div class="alert alert-info" role="alert">
                    {{ session('message') }}
                </div>
            @endif

                <img class="main-logo" src="/images/images_for_promouter/Logo.png">

            <form method="POST" action="{{ route('login') }}">
                @csrf


                <div class="inputs">

{{------------------------------------ Login -----------------------------------}}

                    <div class="form-group">

                        <input id="email" name="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}" value="{{ old('email', null) }}">

                        @if ($errors->has('email'))
                            <span class="help-block" style="color:red; font-size: 14px;">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                        @endif

                    </div>

{{------------------------------------- Password --------------------------------}}

                    <div class="form-group">
                        <div class="row">


                        </div>
                        <div class="input-group input-group-merge">
                            <input id="password" name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required placeholder="{{ trans('global.login_password') }}">
                            @if($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                        </div>
                    </div>

{{-------------------------------- Send and Forgot Password-----------------------}}

                    <button type="submit" class="login-button button btn">Войти</button>

                    <div class="col-auto">
                        @if(Route::has('password.request'))
                            <a class="forgot-password-button" href="{{ route('password.request') }}">
                                {{ trans('global.forgot_password') }}
                            </a><br>
                        @endif
                    </div>

                </div>

            </form>
        </div>
    </main>
@endsection



