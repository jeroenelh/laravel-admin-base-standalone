<?php

namespace Microit\LaravelAdminBaseStandalone;

use Collective\Html\FormBuilder as IlluminateFormBuilder;

class FormBuilder extends IlluminateFormBuilder {

    /**
     * Render a file field with material markup
     * @param $name
     * @param array $settings
     * @return string
     */
    public function generateFile($name, $settings = [])
    {
        $attributes = $this->compileAttributes($settings);
        $attributes['placeholder'] = array_get($settings, 'placeholder');

        $class_wrapper = '';
        if (array_get($settings, 'cols')) {
            $class_wrapper .= ' col s12 m'.array_get($settings, 'cols').' ';
        }

        $return = '<div class="file-field input-field '.$class_wrapper.'">';
        $return.= '    <div class="waves-effect waves-light btn">';
        $return.= '        <span>Bestand</span>';
        $return.=          $this->file($name, array_get($settings, 'value'), $attributes);
        $return.= '    </div>';
        $return.= '    <div class="file-path-wrapper">';
        $return.= '        <input class="file-path validate" type="text" />';
        $return.= '    </div>';
        $return.= '</div>';
        return $return;
    }

    /**
     * Render a textarea with material markup
     *
     * @param $name
     * @param $value
     * @param array $settings
     * @return string
     */
    public function generateTextarea($name, $value, $settings = [])
    {
        if (!isset($settings['class'])) {
            $settings['class'] = 'materialize-textarea';
        }
        $class_wrapper = '';
        if (array_get($settings, 'cols')) {
            $class_wrapper .= ' col s12 m'.array_get($settings, 'cols').' ';
        }
        $return = '<div class="input-field '.$class_wrapper.'">';
        $return.=   $this->textarea($name, $value, $settings);
        if (isset($settings['lang'])) {
            $return .= ' <label>'.\Lang::get($settings['lang']).'</label>';
        } else {
            $return .= ' <label>'.$name.'</label>';
        }
        $return.= '</div>';

        return $return;
    }


    /**
     * Render a submit button with material markup
     *
     * @param $name
     * @param array $settings
     * @return mixed
     */
    public function generateSubmit($name, $settings = [])
    {
        if (!isset($settings['class'])) {
            $settings['class'] = 'waves-effect waves-light btn';
        }
        return $this->submit($name, $settings);
    }

    /**
     * Save data attributes to normal attributes
     * @param array $settings
     * @return mixed
     */
    private function compileAttributes($settings = [])
    {
        $attributes = array_where($settings, function ($key, $value) {
            return (starts_with($key, 'data-') || 'id' == $key) ? $value : null;
        });

        return $attributes;
    }
}