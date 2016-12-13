<div class="form-group col-md-{{ $field_cols }} {{ $class_error }}">
    @if(isset($data['package']) && !is_null($data['package']))
        <label>@lang($data['package']."::".$model_snake_case.".".$field_name)</label>
    @else
        <label>@lang("models.".$model_snake_case.".".$field_name)</label>
    @endif
    <div style="height: 34px;line-height: 34px;">
        <label>
            {{ Form::radio($field_name, true, ($model->$field_name == true)) }}
            @if(isset($data['package']) && !is_null($data['package']))
                @lang($data['package']."::form.boolean.yes")
            @else
                @lang("models.form.boolean.yes")
            @endif
        </label> &nbsp;
        <label>
            {{ Form::radio($field_name, false, ($model->$field_name == false)) }}
            @if(isset($data['package']) && !is_null($data['package']))
                @lang($data['package']."::form.boolean.no")
            @else
                @lang("models.form.boolean.no")
            @endif
        </label>
    </div>
</div>