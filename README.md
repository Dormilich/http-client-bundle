# http-client bundle

Symfony 5 bundle for `dormilich/http-client`.

## Installation

This bundle requires Symfony 5 as well as a [PSR-17](https://www.php-fig.org/psr/psr-17/) and
[PSR-18](https://www.php-fig.org/psr/psr-18/) implementation.

- [PSR-17 libraries](https://packagist.org/providers/psr/http-factory-implementation)
- [PSR-18 libraries](https://packagist.org/providers/psr/http-client-implementation)

However, it makes sense to use `symfony/http-client` as PSR-18 implementation in a Symfony project.

You can then install this bundle via composer:
```
composer require dormilich/http-client-bundle
```

## Configuration

The configuration allows the predefined JSON- and URL-transformers to be set up. By default, the
JSON-transformers are set up without options and the URL-transformers with the `php` strategy.

The JSON-encoder/decoder accepts `JSON_*` constants as constructor argument. They can be added
using the `encoder.json`/`decoder.json` key.

The URL-encoder/decoder can be configured with two encoding strategies, `php` (native PHP parsing
strategy, used for populating `$_GET` and `$_POST`) and `nvp` (a strategy that uses `name=value`
pairs) using the `encoder.url`/`decoder.url` key.

Example:
```yaml
# config/packages/dormilich_http_client.yaml
dormilich_http_client:
  encoder:
    url: php
  decoder:
    json: !php/const JSON_OBJECT_AS_ARRAY
```

### Tagging

The bundle allows the HTTP client to be configured in `services.yaml` using service tags.

- `dormilich_http_client.client_decoder`: Add tagged decoder to the client
- `dormilich_http_client.client_encoder`: Add tagged encoder to the client
- `dormilich_http_client.client_transformer`: Add tagged data transformer/encoder/decoder to the client

Examples:
```yaml
# add error decoder to every client instance
Dormilich\HttpClient\Decoder\ErrorDecoder:
  tags:
    - dormilich_http_client.client_decoder
```
```yaml
# encode JSON objects
Dormilich\HttpClient\Transformer\JsonEncoder:
    tags:
        - dormilich_http_client.client_transformer
```
