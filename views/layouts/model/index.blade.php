@extends('laravel-admin-base-standalone::layouts.model.content_box')

@section('page-content-box')
    <?php
    $model = $data['model'];
    if (is_object($data['model'])) {
        $model = get_class($data['model']);
    }
    $model_reflection = new \ReflectionClass($model);
    $model_name = $model_reflection->getShortName();
    $model_snake_case = snake_case($model_name);
    ?>
    <div class="col-md-12">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <?php
                $filter_available = false;
                $order = \Illuminate\Support\Facades\Input::get('order');
                ?>
                @foreach($data['table']['fields'] as $field_name => $field)
                    <?php
                    if (!is_array($field)) {
                        $field_name = $field;
                    }

                    $class = '';
                    if (isset($field['class'])) {
                        $class .= $field['class'];
                    }

                    if (isset($field['filter'])) {
                        $filter_available = true;
                    }

                    // order
                    $order_direction = isset($order[$field_name]) ? $order[$field_name]=='asc' ? 'desc': 'asc' : 'asc';
                    $order_icon = isset($order[$field_name]) ? $order[$field_name]=='desc' ? 'fa fa-chevron-down': 'fa fa-chevron-up' : '';
                    ?>
                    <th class="{{ $class }}">
                        @if(strpos($field_name, ".")===false)
                        <a href="?order[{{$field_name}}]={{ $order_direction }}">
                        @endif
                            @if(isset($data['package']) && !is_null($data['package']))
                                @lang($data['package']."::".$model_snake_case.".".$field_name)
                            @else
                                @lang("models.".$model_snake_case.".".$field_name)
                            @endif
                            <i class="{{ $order_icon }} pull-right"></i>
                        @if(strpos($field_name, ".")===false)
                        </a>
                        @endif
                    </th>
                @endforeach
                <th class="col-md-3">Acties</th>
            </tr>
            @if($filter_available)
            <tr class="filter">
                {{ Form::open(['method' => 'get']) }}
                {{ Form::hidden('page', 1) }}
                @if($order)
                    @foreach($order as $field => $direction)
                        {{ Form::hidden('order['.$field.']', $direction) }}
                    @endforeach
                @endif
                @foreach($data['table']['fields'] as $field_name => $field)
                    <td>
                        <?php
                        if (!is_array($field)) {
                            $field_name = $field;
                        }
                        $filter = \Illuminate\Support\Facades\Input::get('filter');
                        $value = isset($filter[$field_name]) ? $filter[$field_name] : '';
                        ?>
                        @if (isset($field['filter']))
                            @if(!isset($field['filter']['type']) || $field['filter']['type'] == 'text')
                                {{ Form::text('filter['.$field_name.']', $value, ['class' => 'form-control']) }}
                            @elseif($field['filter']['type'] == 'select' || $field['filter']['type'] == 'select_has')
                                {{ Form::select('filter['.$field_name.']', $field['filter']['data'], $value, ['class' => 'form-control']) }}
                            @endif
                        @endif
                    </td>
                @endforeach
                <th class="col-md-3">
                    {{ Form::submit('Filter', ['class' => 'btn btn-default btn-small']) }}
                </th>
                {{ Form::close() }}
            </tr>
            @endif
            </thead>
            <tbody>
            @foreach($data['collection'] as $item)
                <tr>
                    @foreach($data['table']['fields'] as $field_name => $field)
                        <?php
                        if (!is_array($field)) {
                            $field_name = $field;
                        }

                        $class = '';
                        if (isset($field['class'])) {
                            $class .= $field['class'];
                        }

                        // if there is an dot in the field_name
                        $value = $item->$field_name;
                        $value_short = $item->$field_name;
                        if (strpos($field_name, ".")) {
                            $path = explode(".", $field_name);
                            $path_model = $path[0];
                            $path_field = $path[1];
                            if ($item->$path_model instanceof \Illuminate\Database\Eloquent\Collection) {
                                $values = [];
                                foreach ($item->$path_model as $instance) {
                                    $values[] = $instance->$path_field;
                                }

                                $value = join("\n", $values);
                                $value_short = join(", ", $values);

                                if (count($values) > 3) {
                                    $value_short = $values[0].", ".$values[1].", ".$values[2]." (+".(count($values)-3).")";
                                }
                            } else {
                                $value = $item->$path_model->$path_field;
                                $value_short = $item->$path_model->$path_field;
                            }
                        }

                        // casts
                        $cast = isset($item->getCasts()[$field_name]) ? $item->getCasts()[$field_name] : null;
                        if (!is_null($cast)) {
                            switch ($cast) {
                                case 'boolean':
                                    $value_short = ($value === true) ? 'Ja' : 'Nee';
                                    break;
                            }
                        }
                        ?>
                        <td title="{{ $value }}" class="{{ $class }}">{{ $value_short }}</td>
                    @endforeach
                    <td>
                        <?php
                            $actions = ['show' => 'GET', 'edit' => 'GET', 'destroy' => 'DELETE'];
                        ?>
                        @foreach($actions as $action_name => $action_method)

                            @if(Route::has($data['route'].".".$action_name) && (is_null($data['package']) || \Illuminate\Support\Facades\Auth::user()->may(strtolower($model_name.".".$action_name))))
                            {{ Form::open(['route' => [$data['route'].".".$action_name, $item->id],'method' => $action_method, 'style' => 'display:inline;']) }}
                                <button class="btn btn-default btn-sm">
                                    <span class="{{ trans('laravel-admin-base-standalone::form.'.$action_name.'-icon') }}"></span>
                                    &nbsp; {{ ucfirst(trans('laravel-admin-base-standalone::form.'.$action_name)) }}
                                </button> &nbsp;
                            {{ Form::close() }}
                            @endif
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if(method_exists($data['collection'], 'links'))
            {{ $data['collection']->links() }}
        @endif
    </div>
@endsection