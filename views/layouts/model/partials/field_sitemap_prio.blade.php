<div class="form-group col-md-{{ $field_cols }} {{ $class_error }}">
    @if(isset($data['package']) && !is_null($data['package']))
        <label>@lang($data['package']."::".$model_snake_case.".".$field_name)</label>
    @else
        <label>@lang("models.".$model_snake_case.".".$field_name)</label>
    @endif
    <?php
    $values = [
        '1' =>   '1.0 Hoogste',
        '0.9' => '0.9',
        '0.8' => '0.8',
        '0.7' => '0.7',
        '0.6' => '0.6',
        '0.5' => '0.5 Gemiddeld',
        '0.4' => '0.4',
        '0.3' => '0.3',
        '0.2' => '0.2',
        '0.1' => '0.1 Laagste',
    ];
    $value = is_null($model->$field_name) ? '0.5' : $model->$field_name;
    ?>
    {{ Form::select($field_name, $values, $value, ['class' => 'form-control']) }}
</div>