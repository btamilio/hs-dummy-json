<?php

namespace Btamilio\HsDummyJson\Http\Controllers;

use Btamilio\HsDummyJson\Service\QueryService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Spatie\ArrayToXml\ArrayToXml;

class SearchController extends Controller
{

 
    public function __construct(protected QueryService $service) {}

    public function query(Request $request)
    {
        $customers = [];
        // sanity check
        $validated = $this->service->validate($request);


        // Handle validation errors
        if (isset($validated["errors"])) {
            $response_data = ['errors' => $validated["errors"] ];
            $response_status = 400;
        } else {
 
            $results = $this->service->search($validated);
    
            if (isset($results["errors"])) {
                $response_data = ['errors' => $results["errors"]];
            } else {
                // format results to HelpSpot live-lookup spec
                foreach ($results["users"] ?? [] as $result) {
                    // TODO: this mapping could be in an enum or configuration
                    $customers[] = [
                        "id"          => $result['id'] ?? "",
                        "first_name"  => $result['firstName'] ?? "",
                        "last_name"   => $result['lastName'] ?? "",
                        "email"       => $result['email'] ?? "",
                    ];
                }
                
                // if no results, return a notice instead. there isn't much to the spec here.
                $response_data = (empty($customers))
                                        ? ['notice' => 'No results found']
                                        : ['customer' => $customers];

            }
        }




        $xml = ArrayToXml::convert($response_data, 'livelookup', true, 'UTF-8', '1.0'); //TODO: this could be in configuration
        return new Response($xml, $response_status ?? 200, ['Content-Type' => 'application/xml']);
        
    }
}
