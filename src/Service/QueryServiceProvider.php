<?php

namespace Btamilio\HsDummyJson\Service;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;

class QueryServiceProvider extends ServiceProvider
{


    public function __construct(Application $app)
    {
    }


    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }

    public function search($input) {
            return json_encode($input);

    }
}

 