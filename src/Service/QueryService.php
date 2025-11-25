<?php

namespace Btamilio\HsDummyJson\Service;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Request;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Translation\FileLoader;
use Illuminate\Filesystem\Filesystem;

class QueryService
{

protected Translator $translator;

    public function __construct()
    {
        // load a custom translator without relying on the Laravel container / facades
        $translations = require __DIR__ . '/../../lang/en/validation.php';
        $loader = new ArrayLoader();       
        $loader->addMessages('en', "validation", $translations);

        $this->translator = new Translator($loader, 'en');

    }



    public function validate(Request $request): array
    {
        // Build a validator without relying on the Laravel container / facades
        $validator = (new Factory($this->translator))
            ->make(
                $request->all(),
                [
                    'q' => 'required|string|max:100',
                ], 
            );

        if ($validator->fails()) {
            return ['errors' => $validator->errors()->all()];
        }

        return $validator->validated();
    }

    public function search(array $input): array
    {
        $http = new HttpFactory();

        return $http
            ->get('https://dummyjson.com/users/search', ['q' => $input['q'] ?? ''])
            ->json();
    }
}

