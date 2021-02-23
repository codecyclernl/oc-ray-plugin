<?php namespace Codecycler\Ray;

use Backend;
use Spatie\Ray\Ray;
use Spatie\Ray\Client;
use System\Classes\PluginBase;
use Spatie\Ray\Settings\Settings;
use Spatie\Ray\Settings\SettingsFactory;

/**
 * Ray Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Ray',
            'description' => 'No description provided yet...',
            'author'      => 'Codecycler',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Settings::class, function () {
            $settings = SettingsFactory::createFromConfigFile($this->app->configPath());

            return $settings->setDefaultSettings([
                'enable' => ! app()->environment('production'),
                'send_cache_to_ray' => false,
                'send_dumps_to_ray' => true,
                'send_jobs_to_ray' => false,
                'send_log_calls_to_ray' => true,
                'send_queries_to_ray' => false,
                'send_requests_to_ray' => false,
                'send_views_to_ray' => false,
            ]);
        });

        $settings = app(Settings::class);

        $this->app->bind(Client::class, function () use ($settings) {
            return new Client($settings->port, $settings->host);
        });

        $this->app->bind(Ray::class, function () {
            $client = app(Client::class);
            $settings = app(Settings::class);

            $ray = new Ray($settings, $client);

            if (!$settings->enable) {
                $ray->disable();
            }

            return $ray;
        });
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Codecycler\Ray\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'codecycler.ray.some_permission' => [
                'tab' => 'Ray',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'ray' => [
                'label'       => 'Ray',
                'url'         => Backend::url('codecycler/ray/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['codecycler.ray.*'],
                'order'       => 500,
            ],
        ];
    }
}
