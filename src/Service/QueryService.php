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
            'last_name'  => 'nullable|string|required_without_all:first_name,company_id,email,q',
            'first_name' => 'nullable|string|required_without_all:last_name,company_id,email,q',
            'company_id' => 'nullable|numeric|required_without_all:last_name,first_name,email,q',
            'email'      => 'nullable|email|required_without_all:last_name,first_name,company_id,q',
            'q'          => 'nullable|string|required_without_all:last_name,first_name,company_id,email',
        ]);

 
        if ($validator->fails() ) {
            return ['errors' => $validator->errors()->all()];
        }

        return $validator->validated();
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

