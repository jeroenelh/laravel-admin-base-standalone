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
        <p class="login-box-msg">Wachtwoord reset</p>

        @if (isset($errors) && count($errors))
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        {{ Form::open(['url' => url('/password/reset')]) }}
        {{ Form::hidden('token', $token) }}
        <div class="form-group has-feedback">
            {{ Form::email('email', $email ? : old('email'), ['class' => 'form-control', 'placeholder' => 'Gebruikersnaam']) }}
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Wachtwoord']) }}
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Wachtwoord herhalen']) }}
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Wachtwoord opnieuw instellen</button>
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
