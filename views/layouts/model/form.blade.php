@extends('laravel-admin-base-standalone::layouts.model.normal')

@section('page-content')
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
    @if(isset($model['form_advanced']))
    <div class="row">
        @foreach($model['form_advanced'] as $col)
            <div class="{{ $col['class'] }}">
                @foreach($col['boxes'] as $box_name => $box_info)
                    @if($box_name === 'locales' && method_exists($model, 'locales'))
                        <!-- LOCALES -->
                        <?php
                        $locales = \Microit\LaravelAdminCms\Models\Locale::all();

                        $modelReflection = new \ReflectionClass($model);
                        $modelLocaleName = $modelReflection->getShortName()."Locale";
                        $modelLocaleSnake = snake_case($modelLocaleName);
                        ?>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                @foreach($locales as $locale)
                                    <?php $class = ($locale->is_default==1) ? 'active' : ''; ?>
                                    <li class="{{ $class }}">
                                        <a href="#Locale{{ $locale->slug }}" data-toggle="tab">{{ $locale->display_title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                @foreach($locales as $locale)
                                    <?php
                                    $class = ($locale->is_default==1) ? 'active' : '';
                                    $modelLocale = $data['model']->locale($locale->id);
                                    ?>
                                    <div id="Locale{{ $locale->slug }}" class="{{ $class }} localeSection tab-pane">
                                        @if(isset($modelLocale->id))
                                            {{ Form::hidden($modelLocaleSnake.'['.$locale->slug.'][id]', $modelLocale->id) }}
                                        @endif
                                        @if(is_string($modelLocale['form']))
                                            @include($modelLocale['form'])
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- NO LOCALES -->
                        <div class="box box-info">
                            @if(!is_numeric($box_name))
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{ $box_name }}</h3>
                                    <div class="box-tools pull-right">
                                        <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                            @endif
                            <div class="box-body">
                                @include('laravel-admin-base-standalone::layouts.model.partials.fields', ['model' => $data['model'], 'fields' => $box_info])
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endforeach
    </div>
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                    <?php
                    if(!isset($data['model']['form'])) {
                        $data['model']['form'] = [$data['model']['fillable']];
                    }
                    ?>
                    @foreach($data['model']['form'] as $name => $fields)
                        @if(!is_int($name))
                            @if(array_keys($data['model']['form'])[0] != $name)
                                <hr>
                            @endif
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <h4 class="no-margin">{{ $name }}</h4>
                                </div>
                            </div>
                        @endif
                        @include('laravel-admin-base-standalone::layouts.model.partials.fields', ['model' => $data['model'], 'fields' => $fields])
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('page-content-test')
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

    <?php
    $hasLocale = method_exists($data['model'], 'locales');
    ?>
    {{ Form::open(['url' => $data['form']['route'] ,'method' => $data['form']['method'], 'files' => true]) }}
        @if(is_object($data['model']) && !is_null($data['model']->id))
            {{ Form::hidden('id', $data['model']->id) }}
        @endif

        {{--@include('laravel-admin-base::layouts.model.partials.fields', ['model' => $data['model']])--}}


        @if($hasLocale)
            <?php
            $locales = \Microit\LaravelAdminCms\Models\Locale::all();
            $modelLocale = is_object($data['model']) ? get_class($data['model'])."Locale" : $data['model']."Locale";
            $modelLocale = new $modelLocale();
            ?>
            <ul class="nav nav-tabs">
                @foreach($locales as $index => $locale)
                    <?php $class = ($locale->is_default==1) ? 'active' : ''; ?>
                    <li class="{{ $class }}">
                        <a href="#Locale{{ $locale->slug }}" data-toggle="tab">{{ $locale->display_title }}</a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content clearfix">
                @foreach($locales as $index => $locale)
                <div class="tab-pane active" id="Locale{{ $locale->slug }}">
                    {{--@include('laravel-admin-base::layouts.model.partials.fields', ['model' => $modelLocale, 'locale' => $locale])--}}
                </div>
                @endforeach
            </div>
        @else

        @endif

        <div class="row">
            <div class="form-group col-md-12">
                {{ Form::submit('Opslaan', ['class' => 'btn btn-primary']) }}
            </div>
        </div>
    {{ Form::close() }}
@endsection
@section('scripts_footer')
    @parent
    <script>
        $(document).ready(function(){
            $('.localeSection').each(function(index){
                var browser_title = $(this).find('.browser_title').val();
                var content_title = $(this).find('.content_title').val();
                var navigation_title = $(this).find('.navigation_title').val();
                var slug = $(this).find('.slug').val();

                if (
                    browser_title == content_title &&
                    content_title == navigation_title &&
                    slug == slugify(browser_title)
                ) {
                    $(this).data('change_titles', true);
                } else {
                    $(this).data('change_titles', false);
                }
            });

            $('.localeSection input.browser_title').bind('keyup', function(){
                if ($(this).parents('.localeSection').data('change_titles') == true) {
                    var title = $(this).val();
                    $(this).parents('.localeSection').find('.content_title').val(title);
                    $(this).parents('.localeSection').find('.navigation_title').val(title);
                    $(this).parents('.localeSection').find('.slug').val(slugify(title));
                }
            });

            if (
                $("input[name='slug']").length == 1 &&
                $("input[name='display_title']").length == 1 &&
                $("input[name='slug']").val() == slugify($("input[name='display_title']").val())
            ) {
                $("input[name='display_title']").bind('keyup', function() {
                    console.log("Hi!");
                    var display_title = $("input[name='display_title']").val();
                    var slug = $("input[name='slug']").val();
                    slug = slugify(display_title);
                    $( "input[name='slug']" ).val(slug);
                });
            }
        });

        function slugify(text)
        {
            return text.toString().toLowerCase().trim()
                    .replace(/\s+/g, '-')           // Replace spaces with -
                    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                    .replace(/^-+/, '')             // Trim - from start of text
                    .replace(/-+$/, '');            // Trim - from end of text
        }
    </script>
@endsection