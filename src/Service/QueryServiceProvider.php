<?php

namespace Btamilio\HsDummyJson\Service;

use Illuminate\Support\ServiceProvider;

class QueryServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    public function search($input) {
            return json_encode($input);

    }
}

 