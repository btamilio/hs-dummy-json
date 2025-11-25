<?php

namespace Btamilio\HsDummyJson\Service;

 
 
use Illuminate\Support\Facades\Http;


class QueryService
{

 

    public function search(array $input) {
       return Http::get('https://dummyjson.com/users/search?q='.$input["q"])->json();
    }

    
}

 