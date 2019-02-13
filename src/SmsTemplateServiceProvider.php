<?php

namespace Wangyuanhui\SmsTemplate;

use Illuminate\Support\ServiceProvider;

/**
 * Class SmsTemplateServiceProvider
 * @package Wangyuanhui\SmsTemplate
 */
class SmsTemplateServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return null
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/smstemplate.php' => config_path('smstemplate.php')
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations/create_sms_templates_table.php.stub' => $this->getMigrationFileName(),
        ], 'migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Merge configs
        $this->mergeConfigFrom(
            __DIR__.'/../config/smstemplate.php', 'smstemplate'
        );

        // Bind SmsTemplate
        $this->app->singleton('wangyuanhui.sms-template', function($app) {
            return new SmsTemplate();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'wangyuanhui.sms-template',
        ];
    }

    /**
     * @return string
     */
    protected function getMigrationFileName()
    {
        $timestamp = date('Y_m_d_His');
        return $this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR."{$timestamp}_create_sms_templates_table.php";
    }
}
