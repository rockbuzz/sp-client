# Sendportal Client

Simple SDK for communication with sendportal api.

<p><img src="https://github.com/rockbuzz/sp-client/workflows/Main/badge.svg"/></p>

## Requirements

PHP >=7.2

## Development environment

### Requirements
* [Docker](https://docs.docker.com/get-docker/)
* [Docker Compose](https://docs.docker.com/compose/install/)

```bash
docker-compose up -d --build
```

```bash
docker-compose exec app composer ...
```

### Usage
```bash
composer require rockbuzz/sp-client
```

```php
use Rockbuzz\SpClient\Client;
use Rockbuzz\SpClient\Data\{Subscriber, Tag, Campaign};

$client = new Client;
$client->campaigns(int $page = 1): array;
$client->campaign(int $id): Campaign;
$client->addCampaign(array $data): Campaign;
$client->tags(int $page = 1): array;
$client->tag(int $id): Tag;
$client->addTag(array $data): Tag;
$client->subscribers(int $page = 1): array;
$client->subscriber(int $id): Subscriber
$client->addSubscriber(array $data): Subscriber;
$client->send(int $id): Campaign
```

### Style Code

``` bash
composer cs
```
### Testing

``` bash
composer test
```

## License

The Sendportal Api Client is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).