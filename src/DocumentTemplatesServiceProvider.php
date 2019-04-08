<?php


namespace BWF\DocumentTemplates;


use Illuminate\Support\ServiceProvider;

class DocumentTemplatesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'document-templates');
        $this->loadMigrationsFrom(__DIR__ . '/../database/');
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations'),
            ], 'migrations');

            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/document-templates'),
            ], 'views');

            $this->publishes([
                __DIR__ . '/../resources/js/components' => base_path('resources/js/vendor/document-templates/components'),
            ], 'components');

            $this->publishes([
                __DIR__ . '/../config/document_templates.php' => config_path('document_templates.php'),
            ], 'config');
        }
    }
}