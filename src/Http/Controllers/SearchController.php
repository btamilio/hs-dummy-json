<?php

namespace Btamilio\HsDummyJson\Http\Controllers;

use Btamilio\HsDummyJson\Service\QueryService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

 

use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    public function __construct(protected QueryService $service) {}

    public function query(Request $request)
    {
   
        $validated = $this->service->validate($request);

        // Handle validation errors
        if (isset($validated["errors"])) {
            $errorXml = ArrayToXml::convert(
                ['errors' => $validated["errors"]],
                'livelookup',
                true,
                'UTF-8',
                '1.0'
            );
            return new Response($errorXml, 400, ['Content-Type' => 'application/xml']);
        }


        $results = $this->service->search($validated);

        foreach ($results["users"] as &$result) {
            $result = [
                "first_name" => $result['firstName'] ?? "",
                "last_name"  => $result['lastName'] ?? "",
                "email"      => $result['email'] ?? "",
            ];
        }

        $xml = ArrayToXml::convert(
            ['customer' => $results["users"] ?? [] ],
            'livelookup',
            true,
            'UTF-8',
            '1.0'
        );

        return new Response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
