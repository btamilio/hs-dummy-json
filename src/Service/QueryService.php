<?php

namespace Btamilio\HsDummyJson\Service;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Request;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;


class QueryService
{

    protected Translator $translator;
    protected string $endpoint = 'https://dummyjson.com/users';

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
        $validator = (new Factory($this->translator))->make($request->all(),  [

            
                'last_name' => 'sometimes|string',
                'first_name' => 'sometimes|string',
                'company_id' => 'sometimes|numeric', // TODO: validate against existing company IDs in DB
                'email' => 'sometimes|email',
                'q' => 'sometimes|string', // this isn't in the spec, but it is useful for testing
        ]);

 
        // make sure at least one field is present
        $validator->after(function ($validator) use ($request) {
            if (! $request->filled(['last_name', 'first_name', 'company_id', 'email', 'q'])) {
                $validator->errors()->add('q', 'At least one of last_name, first_name, company_id, email, or q is required.');
            }
        });

 
        return ($validator->errors()->isNotEmpty())
            ?   ['errors' => $validator->errors()->all()]
            : $validator->validated();
  
    }

 
    public function search(array $input): array
    {
        $http = new HttpFactory();
        $query = NULL;
 
        // dummyjson doesn't support complex queries, so for simplicity, we will just use 'q' to search the first field we find,
        // though we cold also use filter instead of search if it were a specific field...
 
        foreach (array_filter($input) as $key => $value) { //FIFO
            if (is_null($query) && in_array($key, ['q', 'first_name', 'last_name', 'email', 'company_id'])) {
                    $query = $value;
            }
        }
         
        return ($query) 
            ? $http->get($this->endpoint . "/search", ["q" => $query, "select" => ["id", "firstName", "lastName", "email"] ])->json()
            : ['errors' => "expected at least one searchable field (q, first_name, last_name, email, company_id)"];
  
    }
}

