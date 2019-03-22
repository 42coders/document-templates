<?php


namespace BWF\DocumentTemplates;


use Illuminate\Support\ServiceProvider;

class DocumentTemplatesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'document-templates-migrations');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/document-templates'),
            ], 'document-templates-views');

            $this->publishes([
                __DIR__.'/../resources/js/components' => base_path('resources/js/components/document-templates'),
            ], 'passport-components');
        }
    }
}