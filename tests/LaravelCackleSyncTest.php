<?php

namespace Aleksei4er\LaravelCackleSync\Tests;

use Aleksei4er\LaravelCackleSync\Facades\LaravelCackleSync;
use Aleksei4er\LaravelCackleSync\ServiceProvider;
use Orchestra\Testbench\TestCase;

class LaravelCackleSyncTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'laravel-cackle-sync' => LaravelCackleSync::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
