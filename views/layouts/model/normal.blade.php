@extends('laravel-admin-base-standalone::main')
@section('content')
    <div class="content-wrapper">
        @if (isset($data['form']) && isset($data['form']['route']))
        {{ Form::open(['url' => $data['form']['route'], 'method' => $data['form']['method'], 'files' => true]) }}
            @if(isset($data['model']->id))
                {{ Form::hidden('id', $data['model']->id) }}
            @endif
        @endif
        @if(isset($data['title']))
        <section class="content-header">
            <h1 class="pull-left" style="margin-bottom: 10px;">
                {{ $data['title'] }}
                @if(isset($data['subtitle']))
                    <small>{{ $data['subtitle'] }}</small>
                @endif
            </h1>

            @if(isset($data['title_link']))
                <a href="{{ route($data['title_link']['route']) }}" class="btn btn-success pull-right" style="margin-bottom: 10px;">
                    {{ $data['title_link']['title'] }}
                </a>
            @endif
            @if (isset($data['form']) && isset($data['form']['route']))
                <div class="pull-right">
                    {{ Form::submit('Opslaan',['class' => 'form-control btn-primary']) }}
                </div>
            @endif
        </section>
        <div class="clearfix"></div>
        @endif
        <section class="content">
            @yield('page-content')
        </section>
        @if (isset($data['form']) && isset($data['form']['route']))
        {{ Form::close() }}
        @endif
    </div>
@stop