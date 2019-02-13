<?php

namespace Wangyuanhui\SmsTemplate\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model class for SmsTemplate
 * @package Wangyuanhui\SmsTemplate
 */
class SmsTemplate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'language', 'group'
    ];
}
