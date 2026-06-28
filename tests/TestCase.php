<?php

namespace Kodamity\Libraries\ApiUsagePulse\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use RefreshDatabase;
    use WithWorkbench;

    protected $enablesPackageDiscoveries = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app['view']->prependNamespace(
            'pulse',
            dirname(__DIR__) . '/workbench/resources/views/vendor/pulse',
        );

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Kodamity\\Libraries\\ApiUsagePulse\\Database\\Factories\\' . class_basename($modelName) . 'Factory',
        );
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('x', 32)));
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
