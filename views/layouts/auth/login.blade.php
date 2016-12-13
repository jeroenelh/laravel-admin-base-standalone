<!DOCTYPE html>
<html>
<head>
    @include('laravel-admin-base-standalone::partials.head')
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="/"><b>{{ config('laravel-admin-base-standalone.login_screen_title') }}</b></a>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">Inloggen bij {{ config('laravel-admin-base-standalone.login_screen_title') }}</p>

        @if (isset($errors) && count($errors))
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ Form::open() }}
        <div class="form-group has-feedback">
            {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Gebruikersnaam']) }}
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Wachtwoord']) }}
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label>
                        {{ Form::checkbox('remember') }} Onthoud me
                    </label>
                </div>
            </div>
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
            </div>
            <div>

                <a class="btn btn-link" href="{{ url('/password/reset') }}">
                    Wachtwoord vergeten?
                </a>
            </div>
        </div>
        {{ Form::close() }}

    </div>
</div>
@include('laravel-admin-base-standalone::partials.foot')
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>
