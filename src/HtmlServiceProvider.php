<?php

namespace Microit\LaravelAdminBaseStandalone;

use Collective\Html\HtmlServiceProvider as IlluminateHtmlServiceProvider;

class HtmlServiceProvider extends IlluminateHtmlServiceProvider
{

    /**
     * Register the form builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton('form', function ($app) {
            $form = new FormBuilder($app['html'], $app['url'], $app['view'], $app['session.store']->getToken());
            return $form->setSessionStore($app['session.store']);
        });
    }
}