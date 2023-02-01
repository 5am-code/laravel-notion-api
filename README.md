<h1 align="center"> Notion for Laravel</h1>

<img src="https://banners.beyondco.de/Notion%20for%20Laravel.png?theme=light&packageManager=composer+require&packageName=fiveam-code%2Flaravel-notion-api&pattern=architect&style=style_1&description=Effortless+Notion+integrations+with+Laravel&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg">

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fiveam-code/laravel-notion-api.svg?style=flat-square)](https://packagist.org/packages/fiveam-code/laravel-notion-api)
[![Total Downloads](https://img.shields.io/packagist/dt/fiveam-code/laravel-notion-api.svg?style=flat-square)](https://packagist.org/packages/fiveam-code/laravel-notion-api)

[comment]: <> (![GitHub Actions]&#40;https://github.com/fiveam-code/laravel-notion-api/actions/workflows/main.yml/badge.svg&#41;)

This package provides a simple and crisp way to access the Notion API endpoints, query data and update existing entries.


## Installation

1. You can install the package via composer:

    ```bash
    composer require fiveam-code/laravel-notion-api
    ```


2. Get your Notion API access token like explained in [their documentation](https://developers.notion.com/). It's also
important to grant access to the integration within your Notion pages, which is described in the developer documentation at Notion as well.

3. For internal Integrations, please add a new entry to your `.env` like the following:

    ```bash
    NOTION_API_TOKEN="$YOUR_ACCESS_TOKEN"
    ```
4. Now you can easily access Notion:
    ```php
    use \Notion;
    
    Notion::databases()->find($databaseId);
    ```

    That's it.


## Usage

Head over to the [Documentation](https://notionforlaravel.com) of this package.

### 🔥 Code Examples to jumpstart your next Notion API Project

#### Fetch a Notion Database (through a Facade)
```php
use \Notion; 

Notion::databases()
        ->find("a7e5e47d-23ca-463b-9750-eb07ca7115e4");
```

#### Fetch a Notion Page
```php
Notion::pages()
        ->find("e7e5e47d-23ca-463b-9750-eb07ca7115e4");
```

#### Search
```php
// Returns a collection pages and databases of your workspace (included in your integration-token)
Notion::search("My Notion Search")
        ->query()
        ->asCollection();
```

#### Query Database

```php
// Queries a specific database and returns a collection of pages (= database entries)
$sortings = new Collection();
$filters = new Collection();

$sortings->add(Sorting::propertySort('Ordered', 'ascending'));
$sortings->add(Sorting::timestampSort('created_time', 'ascending'));

$filters->add(Filter::textFilter('title', ['contains' => 'new']));
// or
$filters->add(Filter::rawFilter('Tags', ['multi_select' => ['contains' => 'great']]));
  
Notion::database("a7e5e47d-23ca-463b-9750-eb07ca7115e4")
      ->filterBy($filters) // filters are optional
      ->sortBy($sortings) // sorts are optional
      ->limit(5) // limit is optional
      ->query()
      ->asCollection();
```


### Testing (pestphp)

```bash
vendor/bin/pest tests
```

## Support

If you use this package in one of your projects or just want to support our development, consider becoming a [Patreon](https://www.patreon.com/bePatron?u=56662485)!

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email hello@dianaweb.dev instead of using the issue tracker.

## Credits

- [Diana Scharf](https://github.com/mechelon)
- [Johannes Güntner](https://github.com/johguentner)


<p align="center">
<img src="https://5amco.de/images/5am.png" width="200" height="200">
</p>

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
