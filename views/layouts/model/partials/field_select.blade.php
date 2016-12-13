<div class="form-group col-md-{{ $field_cols }} {{ $class_error }}">
    @if(isset($data['package']) && !is_null($data['package']))
        <label>@lang($data['package']."::".$model_snake_case.".".$field_name)</label>
    @else
        <label>@lang("models.".$model_snake_case.".".$field_name)</label>
    @endif
    @if(isset($field['database']))
        <?php
        $results = $field['database']['model']::all();
        $values = [];
        foreach ($results as $result) {
            $values[$result->id] = $result->{$field['database']['display']};
        }
        ?>
        {{ Form::select($field_name, $values, $model->$field_name, ['class' => 'form-control']) }}
    @elseif(isset($field['values']))
        {{ Form::select($field_name, $field['values'], $model->$field_name, ['class' => 'form-control']) }}
    @endif
</div>