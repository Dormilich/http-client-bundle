<?php

use Dormilich\Bundle\HttpClientBundle\DependencyInjection\DormilichHttpClientExtension;
use Dormilich\HttpClient\Client;
use Dormilich\HttpClient\Decoder\ErrorDecoder;
use Dormilich\HttpClient\Transformer\DomTransformer;
use Dormilich\HttpClient\Transformer\JsonDecoder;
use Dormilich\HttpClient\Transformer\JsonEncoder;
use Dormilich\HttpClient\Transformer\JsonTransformer;
use Dormilich\HttpClient\Transformer\TextTransformer;
use Dormilich\HttpClient\Transformer\UrlDecoder;
use Dormilich\HttpClient\Transformer\UrlEncoder;
use Dormilich\HttpClient\Transformer\UrlTransformer;
use Dormilich\HttpClient\Transformer\XmlTransformer;
use Dormilich\HttpClient\Utility\NvpQuery;
use Dormilich\HttpClient\Utility\PhpQuery;
use Dormilich\HttpClient\Utility\StatusMatcher;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

/**
 * @covers \Dormilich\Bundle\HttpClientBundle\DependencyInjection\DormilichHttpClientExtension
 */
class HttpClientExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @inheritDoc
     */
    protected function getContainerExtensions(): array
    {
        return [new DormilichHttpClientExtension()];
    }

    /**
     * @test
     */
    public function load_http_client_service()
    {
        $this->load();

        $this->assertContainerBuilderHasService('dormilich_http_client.client', Client::class);
    }

    /**
     * @test
     */
    public function load_query_parser()
    {
        $this->load();

        $this->assertContainerBuilderHasService('dormilich_http_client.nvp_query', NvpQuery::class);
        $this->assertContainerBuilderHasService('dormilich_http_client.php_query', PhpQuery::class);
    }

    /**
     * @test
     */
    public function load_classname_aliases()
    {
        $this->load();

        $this->assertContainerBuilderHasAlias(Client::class, 'dormilich_http_client.client');
        $this->assertContainerBuilderHasAlias(ErrorDecoder::class, 'dormilich_http_client.error_decoder');
        $this->assertContainerBuilderHasAlias(DomTransformer::class, 'dormilich_http_client.dom_transformer');
        $this->assertContainerBuilderHasAlias(JsonDecoder::class, 'dormilich_http_client.json_decoder');
        $this->assertContainerBuilderHasAlias(JsonEncoder::class, 'dormilich_http_client.json_encoder');
        $this->assertContainerBuilderHasAlias(TextTransformer::class, 'dormilich_http_client.text_transformer');
        $this->assertContainerBuilderHasAlias(UrlDecoder::class, 'dormilich_http_client.url_decoder');
        $this->assertContainerBuilderHasAlias(UrlEncoder::class, 'dormilich_http_client.url_encoder');
        $this->assertContainerBuilderHasAlias(XmlTransformer::class, 'dormilich_http_client.xml_transformer');
    }

    /**
     * @test
     */
    public function set_json_transformer_options()
    {
        $config['decoder']['json'] = JSON_BIGINT_AS_STRING;
        $this->load($config);

        $this->assertContainerBuilderHasServiceDefinitionWithArgument('dormilich_http_client.json_decoder', 0, JSON_BIGINT_AS_STRING);
    }

    /**
     * @test
     * @testWith ["nvp"]
     *           ["php"]
     */
    public function set_query_parser_for_url_transformer(string $value)
    {
        $config['encoder']['url'] = $value;
        $this->load($config);

        $this->assertContainerBuilderHasAlias('dormilich_http_client.query_encoder', "dormilich_http_client.{$value}_query");
    }
}
