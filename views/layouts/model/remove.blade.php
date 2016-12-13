@extends('laravel-admin-base-standalone::layouts.model.content_box')

@section('page-content-box')
    <?php
    $model = $data['model'];
    if (is_object($data['model'])) {
        $model = get_class($data['model']);
    } else {
        $data['model'] = new $model;
    }
    $model_reflection = new \ReflectionClass($model);
    $model_name = $model_reflection->getShortName();
    $model_snake_case = snake_case($model_name);
    ?>

    @if(count($errors->all()))
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            {{ Form::open(['url' => route($data['route'].".destroy", [$data['model']->id]), 'method' => $data['form']['method']]) }}
            {{ Form::hidden('confirmed', 'true') }}
            {{ Form::hidden('id', $data['model']->id) }}
            <h4>Weet u zeker dat u het volgende record wilt verwijderen?</h4>
            @foreach($data['fields'] as $field_name)
                <div class="form-group">
                    @if(isset($data['package']) && !is_null($data['package']))
                    <label>@lang($data['package']."::".$model_snake_case.".".$field_name)</label>
                    @else
                    <label>@lang("models.".$model_snake_case.".".$field_name)</label>
                    @endif
                    <div>{{ $data['model']->$field_name }}</div>
                </div>
            @endforeach
            {{ Form::submit('Verwijderen', ['class' => 'btn btn-danger']) }}
            {{ Form::close() }}
        </div>
    </div>
@endsection