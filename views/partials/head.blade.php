<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>
    @if(isset($data) && isset($data['title']))
        {{ $data['title'] }} | {{ config('laravel-admin-base-standalone.login_screen_title') }}
    @else
        {{ config('laravel-admin-base-standalone.login_screen_title') }}
    @endif
</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-title" content="Propic-Creative">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
{!! HTML::style('//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css') !!}
{!! HTML::style('//maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css') !!}
{!! HTML::style('//cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css') !!}
<link media="all" type="text/css" rel="stylesheet" href="<?php echo elixir('assets/css/admin.css'); ?>">
<!-- Theme style -->
<link rel="stylesheet" href="/adminlte/dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="/adminlte/dist/css/skins/skin-blue-light.min.css">
<link rel="stylesheet" href="/adminlte/plugins/iCheck/flat/blue.css">
<link rel="stylesheet" href="/adminlte/plugins/iCheck/square/blue.css">
<link rel="stylesheet" href="/adminlte/plugins/morris/morris.css">
<link rel="stylesheet" href="/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
<link rel="stylesheet" href="/adminlte/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="/adminlte/plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->