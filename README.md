# http-client bundle

Symfony 5 bundle for dormilich/http-client.

## Installation

This bundle requires Symfony 5 as well as a [PSR-17](https://www.php-fig.org/psr/psr-17/) and
[PSR-18](https://www.php-fig.org/psr/psr-18/) implementation.

- [PSR-17 libraries](https://packagist.org/providers/psr/http-factory-implementation)
- [PSR-18 libraries](https://packagist.org/providers/psr/http-client-implementation)

You can then install this bundle via composer:
```
composer require dormilich/http-client-bundle
```

## Configuration

The configuration allows the JSON- and URL-transformer to be set up.

The JSON-transformer accepts `JSON_*` constants as constructor argument. They can be added using
the `transformer.json` key. The URL-transformer can be configured with two encoding strategies,
`php` (native PHP parsing strategy, used for populating `$_GET` and `$_POST`) and `nvp` (a strategy
that uses `name=value` pairs) using the  `transformer.url` key.

By default, the JSON-transformer is set up without options and the URL-transformer with the `php`
strategy.

Example:
```yaml
# config/packages/dormilich_http_client.yaml
dormilich_http_client:
  transformer:
    json: !php/const JSON_BIGINT_AS_STRING
```

### Tagging

The bundle allows the HTTP client to be configured in `services.yaml` using service tags.

- `dormilich_http_client.client_decoder`: Add tagged decoder to the client
- `dormilich_http_client.client_encoder`: Add tagged encoder to the client
- `dormilich_http_client.client_transformer`: Add tagged transformer to the client

Transformers can have additional tags that define for which response status codes the transformer
is executed.

- `dormilich_http_client.decode_success`: Only decode successful (2xx) responses
- `dormilich_http_client.decode_error`: Only decode error (>= 400) responses
- `dormilich_http_client.decode_client_error`: Only decode error (4xx) responses
- `dormilich_http_client.decode_server_error`: Only decode error (5xx) responses

Note: These status code tags have no effect on their own.

Examples:
```yaml
# add error decoder to every client instance
Dormilich\HttpClient\Decoder\ErrorDecoder:
  tags:
    - dormilich_http_client.client_decoder
```
```yaml
# encode JSON objects
# decode successful JSON responses
Dormilich\HttpClient\Transformer\JsonTransformer:
    tags:
        - dormilich_http_client.client_transformer
        - dormilich_http_client.decode_success
```
