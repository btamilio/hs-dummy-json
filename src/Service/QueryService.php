<?php

namespace Btamilio\HsDummyJson\Service;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Request;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Exception;

class QueryService
{
    public function validate(Request $request): array
    {
        // Build a minimal validator instance outside the Laravel container
        $validator = (new Factory(new Translator(new ArrayLoader(), 'en')))->make(
            $request->all(),
            [
                'q' => 'required|string|max:100',
            ]
        );

        return $validator->validate();
    }

    public function search(array $input): array
    {
        $http = new HttpFactory();

        return $http
            ->get('https://dummyjson.com/users/search', ['q' => $input['q'] ?? ''])
            ->json();
    }

 
}

