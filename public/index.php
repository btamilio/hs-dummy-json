<?php

require __DIR__ . '/../vendor/autoload.php';

use Btamilio\HsDummyJson\Http\Controllers\SearchController;
use Btamilio\HsDummyJson\Service\QueryService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

// Capture current HTTP request
$request = Request::createFromGlobals();
$queryService = new QueryService();
$controller = new SearchController($queryService);

 
$response = $controller->query($request);

// Normalize response
if ($response instanceof Response) {
	http_response_code($response->getStatusCode());
	foreach ($response->headers->allPreserveCaseWithoutCookies() as $name => $values) {
		foreach ($values as $value) {
			header("$name: $value", false);
		}
	}
	echo $response->getContent();
} else {
	// fallback: raw output
	echo (string) $response;
}