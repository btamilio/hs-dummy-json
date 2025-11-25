<?php

namespace Btamilio\HsDummyJson;

use Illuminate\Contracts\Foundation\Application;

/**
 * @deprecated Use HsDummyJsonServiceProvider instead.
 */
class HsDummyServiceProvider extends HsDummyJsonServiceProvider
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }
}
