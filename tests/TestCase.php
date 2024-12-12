<?php

namespace Decodeblock\Ercaspay\Tests;

use Decodeblock\Ercaspay\ErcaspayServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use ReflectionClass;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Decodeblock\\Ercaspay\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ErcaspayServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        // config()->set('database.default', 'testing');
        // config()->set('ercaspay.secretKey', 'ECRS-TEST-SECRET');
        // config()->set('ercaspay.baseUrl', 'https://gw.ercaspay.com');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-ercaspay_table.php.stub';
        $migration->up();
        */
    }

    public function accessPrivateProperty($object, $propertyName)
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property;
    }

    public function callPrivateMethod($object, $methodName, ...$args)
    {
        $reflection = new ReflectionClass($object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        // Invoking the method and passing arguments
        return $method->invoke($object, ...$args);
    }
}
