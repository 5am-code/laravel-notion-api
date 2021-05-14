<div style="text-align:center">

# Laravel Notion API
## Effortless Notion integrations with Laravel


[![Latest Version on Packagist](https://img.shields.io/packagist/v/fiveam-code/laravel-notion-api.svg?style=flat-square)](https://packagist.org/packages/fiveam-code/laravel-notion-api)
[![Total Downloads](https://img.shields.io/packagist/dt/fiveam-code/laravel-notion-api.svg?style=flat-square)](https://packagist.org/packages/fiveam-code/laravel-notion-api)

[comment]: <> (![GitHub Actions]&#40;https://github.com/fiveam-code/laravel-notion-api/actions/workflows/main.yml/badge.svg&#41;)

<img src="https://5amco.de/images/5am.png" style="width:300px; height:auto;">

</div>

5amcode proudly presents its first Laravel specific package - a wrapper for the Notion API!
This package will provide a simple and crisp way to access the various Notion API endpoints, query data
and manipulate entries.

### Important note!
This package is in an early stage and receives continuious improvements. DO NOT USE IN PRODUCTION.

## Installation

You can install the package via composer:

```bash
composer require fiveam-code/laravel-notion-api
```

Get your Notion API access token like explained in [their documentation](https://developers.notion.com/). 
It's also important to grant access to the integration within your Notion pages, which is described in Notions docs as well.

Add a new entry to your `.env` like the following:
```
NOTION_API_TOKEN="$YOUR_ACCESS_TOKEN"
```

And you're ready to go!

## Usage
It's too early for a documentation, so we collected some working examples in our [Notion Workspace](https://www.notion.so/5amcode/Working-Examples-813998dab4244158b51ea3b25b420c60).

### Testing

```bash
vendor/bin/phpunit tests
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email hello@dianaweb.dev instead of using the issue tracker.

## Credits

-   [Diana Scharf](https://github.com/mechelon)
-   [Johannes Güntner](https://github.com/johguentner)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.



## Laravel Package Boilerplate
This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
