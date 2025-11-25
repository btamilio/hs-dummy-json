<?php

namespace Btamilio\HsDummyJson\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Client\Factory as HttpFactory;

use Illuminate\Support\Facades\Validator;


class QueryService
{

    public function validate(Request $request) 
    {

        $validator = Validator::make(
            $request->all(), [
                 'q' => 'required|string|max:100',
       ]);

      if ($validator->fails()) {
            return ["errors" => $validator->errors()->all() ];
       }
            


 

    }

    public function search(array $input): array
    {
 
        $http = new HttpFactory();
        return $http->get('https://dummyjson.com/users/search', ['q' => $input['q'] ?? ''])->json();
    }

 
}

 
