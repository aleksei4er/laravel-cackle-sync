<?php

namespace Aleksei4er\LaravelCackleSync\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelCackleSync extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-cackle-sync';
    }
}
