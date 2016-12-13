<div class="form-group col-md-{{ $field_cols }} {{ $class_error }}">
    @if(isset($data['package']) && !is_null($data['package']))
        <label>@lang($data['package']."::".$model_snake_case.".".$field_name)</label>
    @else
        <label>@lang("models.".$model_snake_case.".".$field_name)</label>
    @endif
    {{ Form::password($field_name, ['class' => 'form-control']) }}
</div>