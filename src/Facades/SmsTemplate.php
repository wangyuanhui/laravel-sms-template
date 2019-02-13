<?php

namespace Wangyuanhui\SmsTemplate\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for Class Wangyuanhui\SmsTemplate\SmsTemplate
 * @package Wangyuanhui\SmsTemplate
 */
class SmsTemplate extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'wangyuanhui.sms-template';
    }
}
