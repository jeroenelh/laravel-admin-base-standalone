<!DOCTYPE html>
<html>
<head>
    @include('laravel-admin-base-standalone::partials.head')
</head>
<body class="hold-transition skin-blue-light sidebar-mini">
<div class="wrapper">

    @include('laravel-admin-base-standalone::partials.header')

    @include('laravel-admin-base-standalone::partials.navigation-left')

    @yield('content')
    {{--@include('admin.partials.content')--}}

    @include('laravel-admin-base-standalone::partials.footer')
</div>
@include('laravel-admin-base-standalone::partials.foot')
@yield('scripts_footer')
</body>
</html>