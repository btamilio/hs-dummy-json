<?php

namespace Btamilio\HsDummyJson;

use Btamilio\HsDummyJson\Service\QueryService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class HsDummyJsonServiceProvider extends ServiceProvider
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    public function register(): void
    {
        $this->app->singleton(QueryService::class, fn($app) => new QueryService());
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
}
