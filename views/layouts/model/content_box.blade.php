@extends('laravel-admin-base-standalone::layouts.model.normal')
@section('page-content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    @yield('page-content-box')
                </div>
            </div>
        </div>
    </div>
@endsection