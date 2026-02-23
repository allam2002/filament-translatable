<?php

namespace JeffersonGoncalves\FilamentTranslatable\Tests;

use Filament\FilamentServiceProvider;
use Filament\Support\SupportServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use JeffersonGoncalves\FilamentTranslatable\FilamentTranslatableServiceProvider;
use JeffersonGoncalves\FilamentTranslatable\Tests\Fixtures\TestPanelProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            FilamentServiceProvider::class,
            TestPanelProvider::class,
            FilamentTranslatableServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        config()->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
    }

    protected function setUpDatabase(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable();
            $table->json('content')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
        });
    }
}
