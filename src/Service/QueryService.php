<?php

namespace Btamilio\HsDummyJson\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;

use Illuminate\Validation\ValidationException;
use Exception;


class QueryService
{

    public function validate(Request $request) 
    {

 
        try {
            $request->validate( [
                 'q' => 'required|string|max:100',
        ]);
        } catch (ValidationException $e) {
            return  ["errors" => $e->getMessage() ];
        } catch (Exception $e) {
            return  ["errors" => "Could not validate request" ];
        }   

    }

    public function search(array $input): array
    {
 
        $http = new HttpFactory();
        return $http->get('https://dummyjson.com/users/search', ['q' => $input['q'] ?? ''])->json();
    }

 
}

 
