@extends('laravel-admin-base-standalone::main')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Dashboard
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>

        <section class="content">
            @if(session('error'))
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                </div>
            </div>
            @endif




        </section>
    </div>
@stop

@section('scripts_footer')
    <script>

    </script>
@stop