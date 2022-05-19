<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication,DatabaseMigrations;


    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('key:generate');
    }

    protected function initDatabase()
    {

        Artisan::call('migrate');
        Artisan::call('db:seed --class=BookSeeder');
        Artisan::call('db:seed --class=CommentSeeder');
    }

    protected function resetDatabase()
    {
        Artisan::call('migrate:reset');
    }
}
