<?php

namespace Btamilio\HsDummyJson\Http\Controllers;

use Btamilio\HsDummyJson\Service\QueryService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\ArrayToXml\ArrayToXml;

class SearchController extends Controller
{
    public function __construct(protected QueryService $service) {}

    public function query(Request $request)
    {
        try {
            $request->validate([
                "q" => "required|string|max:100",
            ]);
        } catch (ValidationException $e) {
            return response(['errors' => $e->errors()], $e->status ?? 422);
        } catch (\Exception $e) {
            return response(['errors' => ["could not validate input"]], 422);
        }

        $results = $this->service->search($request->all());

        foreach ($results["users"] as &$result) {
            $result = [
                "first_name" => $result['firstName'],
                "last_name"  => $result['lastName'],
                "email"      => $result['email'],
            ];
        }

 

        // build a single XML response (assuming you want <livelookup> wrapper)
        $xml = ArrayToXml::convert(
            ['customer' => $results["users"]],
            'livelookup',
            true,
            'UTF-8',
            '1.0'
        );

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
