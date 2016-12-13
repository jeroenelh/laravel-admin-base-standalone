<?php

namespace Microit\LaravelAdminBaseStandalone;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Microit\LaravelAdminCms\Models\Locale;

abstract class Controller extends BaseController
{
    use ValidatesRequests;

    protected $model;
    protected $package;
    protected $route;
    protected $paginate = 15;

    /**
     * Returns a view to render
     *
     * @param $template
     * @param $with
     * @return View
     * @throws \Exception
     */
    protected function doView($template, $with)
    {
        $this->checkAttributes();

        // Model
        if(!isset($with['model'])) {
            $model = $this->model;
            $with['model'] = new $model();
        }

        if (!isset($with['data']['model'])) {
            $with['data']['model'] = $this->model;
        }
        if (!isset($with['data']['package'])) {
            $with['data']['package'] = $this->package;
        }
        if (!isset($with['data']['form'])) {
            $with['data']['form'] = [
                'method' => 'POST'
            ];
        }
        if (!isset($with['data']['form']['method'])) {
            $with['data']['form']['method'] = 'POST';
        }

        if (!isset($with['app'])) {
            $with['app'] =(App::class);
        }

        return view($template, $with);
    }

    /**
     * Returns a table view with all the data
     *
     * @param $template
     * @param $with
     * @return View
     */
    protected function doIndex($template, $with)
    {
        // Check rights
        if (!is_null($this->package) && !Auth::user()->may(strtolower($this->getModelName().".index"))) {
            Session::flash('error', 'No permission for action: '.strtolower($this->getModelName().".index"));
            return redirect(route('admin.dashboard'));
        }

        if (!isset($with['data']['collection'])) {
            $class = $this->model;
            $collection = $class::whereRaw("1=1");
            if (isset($with['data']['collection_filter'])) {
                foreach ($with['data']['collection_filter'] as $filter) {
                    if (is_array($filter)) {
                        /**
                         * @todo Implement filter array
                         */
                    } else {
                        $collection = $collection->$filter();
                    }
                }
            }

            /**
             * @todo filter user input
             */
            $filter = Input::get('filter');
            $order = Input::get('order', isset($with['data']['collection_order']) ? $with['data']['collection_order'] : null);

            $urlfilters = Session::get('url_filters', []);
            $urlfilters[$class] = Input::get();
            Session::set('url_filters', $urlfilters);

            if ($filter) {
                $collection = $collection->where(function($query) use ($filter, $with) {
                    foreach ($filter as $field => $value) {
                        if (
                            !isset($with['data']['table']['fields'][$field]) ||
                            !isset($with['data']['table']['fields'][$field]['filter'])
                        ) {
                            continue;
                        }

                        if (trim($value) == "") {
                            continue;
                        }

                        $filter = $with['data']['table']['fields'][$field]['filter'];

                        // Check type of filter
                        $type = 'text';
                        if (isset($filter['type'])) {
                            $type = $filter['type'];
                        }

                        if (isset($filter['field'])) {
                            $field = $filter['field'];
                        }

                        switch($type) {
                            case 'text':
                                switch(strtolower($filter['expression'])) {
                                    case 'like':
                                        $query->whereRaw("LOWER(".$field.") LIKE LOWER('%".$value."%')");
                                        break;
                                    case '=':
                                        $query->where($field, $value);
                                        break;
                                }
                                break;
                            case 'select':
                            case 'select_has':
                                if(method_exists($query->getModel(), $field)) {
                                    $relation = $query->getModel()->{$field}();
                                    switch (get_class($relation)) {
                                        case BelongsToMany::class:
                                            $query->whereHas($field, function ($query) use ($relation, $value) {
                                                $query->where('device_id', $value);
                                            });
                                            break;
                                        case BelongsTo::class:
                                            $query->where($relation->getForeignKey(), $value);
                                            break;
                                        default:
                                            throw new \Exception("Unknown relation: " . get_class($relation));
                                    }
                                    break;
                                } else {
                                    $query->where($field, $value);
                                }
                                break;
                        }
                    }
                });

                if ($order) {
                    foreach ($order as $field => $direction) {
                        if (!isset($with['data']['table']['fields'][$field]) || !in_array($direction, ['asc','desc'])) {
                            continue;
                        }
                        $collection = $collection->orderBy($field, $direction);
                    }
                }

                $collection = $collection->paginate($this->paginate)->appends(Input::except('page'));
            } else {
                if ($order) {
                    foreach ($order as $field => $direction) {
                        if (is_numeric($field)) {
                            $field = $direction;
                            $direction = 'asc';
                        }
                        if ((!isset($with['data']['table']['fields'][$field]) && !in_array($field, $with['data']['table']['fields'])) || !in_array($direction, ['asc','desc'])) {
                            continue;
                        }
                        $collection->orderBy($field, $direction);
                    }
                }
                $collection = $collection->paginate($this->paginate)->appends(Input::except('page'));
            }

            $with['data']['collection'] = $collection;
        }
        if (!isset($with['data']['route'])) {
            $with['data']['route'] = $this->route;;
        }

        if (is_null($template)) {
            $template = 'laravel-admin-base-standalone::layouts.model.index';
        }

        return $this->doView($template, $with);
    }

    /**
     * Show a single record
     *
     * @param $template
     * @param $id
     * @param array $with
     * @return View
     */
    protected function doShow($template, $id, $with = [])
    {
        // Check rights
        if (!is_null($this->package) && !Auth::user()->may(strtolower($this->getModelName().".show"))) {
            Session::flash('error', 'No permission for action: '.strtolower($this->getModelName().".show"));
            return redirect(route('admin.dashboard'));
        }

        if (is_null($template)) {
            $template = 'laravel-admin-base-standalone::layouts.model.show';
        }

        // Get class name
        $model_reflection = new \ReflectionClass($this->model);
        $model_name = snake_case($model_reflection->getShortName());

        // Get model info
        $model = $this->model;
        $model = $model::whereId($id)->firstOrFail();

        $with[$model_name] = $model;

        return $this->doView($template, $with);
    }

    /**
     * Render a create form
     *
     * @param $template
     * @param $with
     * @return View
     */
    protected function doCreate($template, $with)
    {
        // Check rights
        if (!is_null($this->package) && !Auth::user()->may(strtolower($this->getModelName().".create"))) {
            Session::flash('error', 'No permission for action: '.strtolower($this->getModelName().".create"));
            return redirect(route('admin.dashboard'));
        }

        if (!isset($with['data']['route'])) {
            $with['data']['route'] = $this->route;
        }

        if (!isset($with['data']['model'])) {
            $model = $this->model;
            $with['data']['model'] = new $model();
        }

        if (!isset($with['data']['form']) || !isset($with['data']['form']['method'])) {
            $with['data']['form']['method'] = "POST";
        }
        if (!isset($with['data']['route']) || !isset($with['data']['form']['route'])) {
            $with['data']['form']['route'] = route($with['data']['route'].".store");
        }

        if (is_null($template)) {
            $template = 'laravel-admin-base-standalone::layouts.model.form';
        }

        return $this->doView($template, $with);
    }

    /**
     * Render an update form
     *
     * @param $template
     * @param $id
     * @param $with
     * @return View
     */
    protected function doUpdate($template, $id, $with)
    {
        // Check rights
        if (!is_null($this->package) && !Auth::user()->may(strtolower($this->getModelName().".edit"))) {
            Session::flash('error', 'No permission for action: '.strtolower($this->getModelName().".edit"));
            return redirect(route('admin.dashboard'));
        }

        if (!isset($with['data']['route'])) {
            $with['data']['route'] = $this->route;
        }

        if (!isset($with['data']['model'])) {
            $model = $this->model;
            $with['data']['model'] = $model::whereId($id)->firstOrFail();
        }

        if (!isset($with['data']['form']) || !isset($with['data']['form']['method'])) {
            $with['data']['form']['method'] = "PUT";
        }
        if (!isset($with['data']['route']) || !isset($with['data']['form']['route'])) {
            $with['data']['form']['route'] = route($with['data']['route'].".update", $with['data']['model']->id);
        }


        if (is_null($template)) {
            $template = 'laravel-admin-base-standalone::layouts.model.form';
        }

        return $this->doView($template, $with);
    }

    /**
     * Render a remove form
     *
     * @param $template
     * @param $id
     * @param $with
     * @return View
     */
    protected function doRemove($template, $id, $with)
    {
        // Check rights
        if (!is_null($this->package) && !Auth::user()->may(strtolower($this->getModelName().".destroy"))) {
            Session::flash('error', 'No permission for action: '.strtolower($this->getModelName().".destroy"));
            return redirect(route('admin.dashboard'));
        }

        if (!isset($with['data']['route'])) {
            $with['data']['route'] = $this->route;
        }

        if (!isset($with['data']['form']) || !isset($with['data']['form']['method'])) {
            $with['data']['form']['method'] = "DELETE";
        }

        if (!isset($with['data']['model'])) {
            $model = $this->model;
            $with['data']['model'] = $model::whereId($id)->firstOrFail();
        }

        if (is_null($template)) {
            $template = 'laravel-admin-base-standalone::layouts.model.remove';
        }

        return $this->doView($template, $with);
    }

    /**
     * Store a new record or update an existing record
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    protected function doStore($request)
    {
        // Check rights
        if (!is_null($this->package) && !Auth::user()->may(strtolower($this->getModelName().".edit"))) {
            Session::flash('error', 'No permission for action: '.strtolower($this->getModelName().".edit"));
            return redirect(route('admin.dashboard'));
        }

        $this->checkAttributes();
        $this->handleRequest($request);
        $input = $request->input();
        $model = $this->model;
        unset($input['_token']);

        // Unset empty password
        if (isset($input['password']) && trim($input['password']) == "") {
            unset($input['password']);
        }

        // Check model
        if (isset($input['id'])) {
            $model = $model::whereId($input['id'])->firstOrFail();
        }

        switch($request->getMethod()) {
            case 'POST':
                $result = $model::create($input);
                break;
            case 'PUT':
                $model->update($input);
                $result = $model;
                break;
            case 'DELETE':
                $model->delete();
                break;
        }

        if (isset($result)) {
            $this->doStoreLocales($request, $result);
        }

        $urlfilter = [];
        $urlfilters = Session::get('url_filters',[]);
        if (isset($urlfilters[$this->get_class_name($model)])) {
            $urlfilter = $urlfilters[$this->get_class_name($model)];
        }
        return redirect(route($this->route.".index", $urlfilter));
    }

    /**
     * Store locales
     * @param $request
     * @param $mainModel
     */
    protected function doStoreLocales($request, $mainModel)
    {
        $input = $request->input();
        $model = $this->model."Locale";
        if (!class_exists($model)) {
            return;
        }

        $model = new $model;
        $model_reflection = new \ReflectionClass($model);
        $model_name = $model_reflection->getShortName();
        $model_snake_case = snake_case($model_name);

        if (!isset($input[$model_snake_case])) {
            return;
        }
        $inputLocales = $input[$model_snake_case];

        foreach ($inputLocales as $localeSlug => $inputLocale) {
            $locale = Locale::where('slug', $localeSlug)->firstOrFail();

            if (isset($inputLocale['id'])) {
                // Find locale and update/delete
                $localeModelObject = $model->whereId($inputLocale['id'])->firstOrFail();

                if (
                    $inputLocale['browser_title'] == "" &&
                    $inputLocale['navigation_title'] == "" &&
                    $inputLocale['content_title'] == ""
                ) {
                    $localeModelObject->delete();
                } else {
                    $localeModelObject->update($inputLocale);
                }
            } else {
                // create model
                if (
                    $inputLocale['browser_title'] != "" &&
                    $inputLocale['navigation_title'] != "" &&
                    $inputLocale['content_title'] != ""
                ) {
                    $inputLocale['locale_id'] = $locale->id;
                    $inputLocale['page_id'] = $mainModel->id;
                    $mainModel->locales()->create($inputLocale);
                }
            }
        }
    }

    private function checkAttributes()
    {
        if (is_null($this->model)) {
            throw new \Exception("Model is not set for controller ".get_called_class());
        }

        if (is_null($this->package)) {
            //throw new \Exception("Package is not set for controller ".get_called_class());
        }

        if (is_null($this->route)) {
            throw new \Exception("Route is not set for controller ".get_called_class());
        }
    }

    protected function handleRequest(Request $request)
    {

    }

    private function getModelName($model = null)
    {
        if (is_null($model)) {
            $model = $this->model;
        }
        $modelReflection = new \ReflectionClass($model);
        return $modelReflection->getShortName();
    }

    private function get_class_name($model)
    {
        if (is_string($model)) {
            return $model;
        } else if (is_object($model)) {
            return get_class($model);
        }
        throw new \Exception("Unknown type for class name");
    }
}
