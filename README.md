<h1 align="center"> Laravel Notion API</h1>
<h2 align="center"> Effortless Notion integrations with Laravel</h2>

<p align="center">
<img src="https://5amco.de/images/5am.png" width="200" height="200">
</p>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fiveam-code/laravel-notion-api.svg?style=flat-square)](https://packagist.org/packages/fiveam-code/laravel-notion-api)
[![Total Downloads](https://img.shields.io/packagist/dt/fiveam-code/laravel-notion-api.svg?style=flat-square)](https://packagist.org/packages/fiveam-code/laravel-notion-api)

[comment]: <> (![GitHub Actions]&#40;https://github.com/fiveam-code/laravel-notion-api/actions/workflows/main.yml/badge.svg&#41;)

This package provides a simple and crisp way to access the Notion API endpoints, query data and update existing entries.

## Installation

You can install the package via composer:

```bash
composer require fiveam-code/laravel-notion-api
```

### Authorization

The Notion API requires an access token and a Notion integration, [the Notion documentation](https://developers.notion.com/docs/getting-started#before-we-begin) explains how this works. It's important to grant access to the integration within your Notion account to enable the API access.

Add your Notion API token to your `.env` file:

```
NOTION_API_TOKEN="$YOUR_ACCESS_TOKEN"
```

## Usage

Head over to the [Documentation](https://www.notion.so/5amcode/Working-Examples-813998dab4244158b51ea3b25b420c60) of this package in Notion.

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

- [Diana Scharf](https://github.com/mechelon)
- [Johannes Güntner](https://github.com/johguentner)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
