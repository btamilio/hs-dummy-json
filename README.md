# hs-dummy-json

  
This PHP composer module is an example live-lookup integration of ["DummyJson User Search"](https://dummyjson.com/users/search?q=John) with ["HelpSpot"](https://helpsot.com) designed to be used with or without the ["Laravel"](https://www.laravel.com) framework.

##  Installation

This repo is not registered with packagist, so the first step is adding it directly into the "repositories" section of your `composer.json` file:

```
"repositories": [
	{
		"type": "vcs",
		"url": "https://github.com/btamilio/hs-dummy-json"
	}
]
```

*Note: you may need to create a repositories section first if your `composer.json` does not already have one.*

Then you may use `composer` as normal to include a package:
```
$ composer require btamilio/hs-dummy-json 
```

From within Laravel, out of the box, the endpoint will be available at https://your.laravel.domain/hs-dummy-json. If using as a standalone app, set up your PHP-supporting web server software's `DocumentRoot` (or equivalent) to resolve `/path/to/your/project/vendor/Btamilio/hs-dummy-json/public/index.php`.

For example, an NGINX configuration file might contain the following (within a more complete host configuration):
```
include /path/to/your/project/vendor/btamilio/hs-dummy-json/public/*;
index index.php;

location / {
	try_files $uri $uri/ /index.php?$query_string;
}
```

##  Usage

This is a simple demo, and DummyJSON cannot accept complex queries, so any of the following query string keys can be used:

| key        | example value     |
| ---------- | ----------------- |
| q          | any_string        |
| first_name | John              |
| last_name  | Johnson           |
| email      | someone@email.com |

If more than one key is present, only the first non-empty value (chosen in order from the table above) will be used.

Depending on your configuration, you should be able to query the endpoint with  `https://your.domain/?first_name=John` as a standalone or or `https://your.laravel.domain/hs-dummy-json/?q=John` within Laravel.

For a working example endpoint, please see `https://hs-dummy-json.on-forge.com`


## TODO
    - Integrate filtering (not just searching)
