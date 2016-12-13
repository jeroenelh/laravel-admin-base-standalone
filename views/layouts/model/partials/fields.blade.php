<?php

$model_reflection = new \ReflectionClass($model);
$model_name = $model_reflection->getShortName();
$model_snake_case = snake_case($model_name);
?>
<div class="row">
    @foreach($fields as $field_name => $field)
        <?php
        if (!is_array($field)) {
            $field_name = $field;
        }

        $field_type = 'text';
        $field_cols = '6';
        $class_error = '';

        // Casts
        if (isset($model['casts'][$field_name])) {
            switch ($model['casts'][$field_name]) {
                case 'integer':
                    $field_type = 'number';
                    break;
                case 'boolean':
                    $field_type = 'boolean';
                    break;
            }
        }

        // Field names
        switch($field_name) {
            case 'sitemap_prio':
                $field_type = 'sitemap_prio';
                break;
        }

        if (is_array($field)) {
            // Type parameter
            if (isset($field['type'])) {
                switch($field['type']) {
                    case 'select':
                    case 'password':
                    case 'number':
                        $field_type = $field['type'];
                        break;
                }
            }
            if (isset($field['cols'])) {
                $field_cols = $field['cols'];
            }
        }

            if (strstr($field_name, "::") && View::exists($field_name)) {
                $field_type = 'template';
            }

        if ($errors->has($field_name)) {
            $class_error = 'has-error';
        }
        ?>
        @if($field_type == 'template')
            @include($field_name)
        @elseif($field_type == 'select')
            @include('laravel-admin-base-standalone::layouts.model.partials.field_select')
        @elseif($field_type == 'password')
            @include('laravel-admin-base-standalone::layouts.model.partials.field_password')
        @elseif($field_type == 'boolean')
            @include('laravel-admin-base-standalone::layouts.model.partials.field_boolean')
        @elseif($field_type == 'sitemap_prio')
            @include('laravel-admin-base-standalone::layouts.model.partials.field_sitemap_prio')
        @else
            <div class="form-group col-md-{{ $field_cols }} {{ $class_error }}">
                @if(isset($data['package']) && !is_null($data['package']))
                    <label>@lang($data['package']."::".$model_snake_case.".".$field_name)</label>
                @else
                    <label>@lang("models.".$model_snake_case.".".$field_name)</label>
                @endif
                {{ Form::$field_type($field_name, $model->$field_name, ['class' => 'form-control']) }}
            </div>
        @endif
    @endforeach
</div>