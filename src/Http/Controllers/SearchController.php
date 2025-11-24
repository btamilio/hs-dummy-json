<?php

namespace Btamilio\HsDummyJson\Http\Controllers;

use Illuminate\Routing\Controller;
use Btamilio\HsDummyJson\Service\QueryServiceProvider;

class SearchController extends Controller
{
    public function __construct(private QueryServiceProvider $service) {}

    public function __invoke()
    {
        return $this->response($this->service->search($this->request()));
    }
}


