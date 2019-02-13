# Sms Template for Laravel 5

Sms Template package for [Laravel5](https://www.laravel.com)

## Installation

Update in your project's `composer.json`, add:

```json
    "repositories": {
        "wangyuanhui/sms-template": {
            "type": "vcs",
            "url": "https://github.com/wangyuanhui/laravel-sms-template.git"
        }
    },

    or 

    "repositories": [
        // ...
        {
            "type": "git",
            "url": "https://github.com/wangyuanhui/laravel-sms-template.git"
        }
    ],
```

Or

Update it with composer:
```
composer config repositories.wangyuanhui/sms-template vcs https://github.com/wangyuanhui/laravel-sms-template.git
```

Then 

run:
```
composer require wangyuanhui/sms-template
```

Finally

publish config and migration file: 
```
$ php artisan vendor:publish
```
you can see:

`[xx] Provider: Wangyuanhui\SmsTemplate\SmsTemplateServiceProvider`

input `xx` and press return

## Do not forget

Find the `providers` key in `config/app.php` and register the SmsTemplate Service Provider.

```php
    'providers' => [
        // ...
        Wangyuanhui\SmsTemplate\SmsTemplateServiceProvider::class,
    ]
```

Find the `aliases` key in `config/app.php`.

```php
    'aliases' => [
        // ...
        'SmsTemplate' => Wangyuanhui\SmsTemplate\Facades\SmsTemplate::class,
    ]
```

## Configuration

Inside `config/smstemplate.php`, default setting:

```php
return [
    'directive' => '{{key}}',
];
```

for example, change it to

```php
return [
    'directive' => '!@{key}',
];
```

## Example Usage

### Facade
```php
SmsTemplate::create('title-1', "this is a message from {{first_name}} {{last_name}}", 'greeting', 'en');
SmsTemplate::directive('variable-key');
SmsTemplate::all();
SmsTemplate::get(1);
SmsTemplate::get('title-1');
SmsTemplate::get('greeting', 'en');
SmsTemplate::compose(1, ['first_name' => 'Jone', 'last_name' => 'Snow']);
SmsTemplate::update(1, 'new title', 'new content', 'greeting', 'xx');
SmsTemplate::delete(1);
SmsTemplate::delete('title-1');
SmsTemplate::delete('greeting', 'en');
```

### Controller
```php
    // [your site path]/Http/routes.php

    $api->get('sms', '\Wangyuanhui\SmsTemplate\SmsTemplateController@index');

    $api->get('sms/{id}', '\Wangyuanhui\SmsTemplate\SmsTemplateController@show');
    
    $api->post('sms', '\Wangyuanhui\SmsTemplate\SmsTemplateController@store');
    // and the request payload:
    {
        "title":"title", //required
        "content":"Guten Tag {{name}}", //required
        "language":"de", //required
        "group":"greeting" //required
    }

    $api->put('sms/{id}', '\Wangyuanhui\SmsTemplate\SmsTemplateController@update');
    // and the request payload:
    {
        "title":"new title", //optional
        "content":"new content", //optional
        "language":"xx", //optional
        "group":"new group" //optional
    }
    
    $api->delete('sms/{id}', '\Wangyuanhui\SmsTemplate\SmsTemplateController@destroy');
    
    $api->post('sms/compose/{id}', '\Wangyuanhui\SmsTemplate\SmsTemplateController@compose');
    // and the request payload:
    {
        "variables": //optional
        {
            "first_name":"Jone",
            "last_name":"Snow"
        }
    }

    $api->options('sms/directive', '\Wangyuanhui\SmsTemplate\SmsTemplateController@directive');
```