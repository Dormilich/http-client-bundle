<?php

use Dormilich\Bundle\HttpClientBundle\DependencyInjection\Configuration;
use Dormilich\Bundle\HttpClientBundle\DependencyInjection\DormilichHttpClientExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionConfigurationTestCase;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * @covers \Dormilich\Bundle\HttpClientBundle\DependencyInjection\Configuration
 */
class ConfigurationTest extends AbstractExtensionConfigurationTestCase
{
    /**
     * @inheritDoc
     */
    protected function getContainerExtension(): ExtensionInterface
    {
        return new DormilichHttpClientExtension();
    }

    /**
     * @inheritDoc
     */
    protected function getConfiguration(): ConfigurationInterface
    {
        return new Configuration();
    }

    /**
     * @test
     */
    public function load_configuration_files()
    {
        $expected['transformer']['json'] = JSON_BIGINT_AS_STRING;
        $expected['transformer']['url'] = 'nvp';

        $sources[] = __DIR__ . '/fixtures/config.yml';
        $sources[] = __DIR__ . '/fixtures/config.xml';

        $this->assertProcessedConfigurationEquals($expected, $sources);
    }

    /**
     * @test
     */
    public function load_invalid_config_fails()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('The value "test" is not allowed for path "dormilich_http_client.transformer.url". Permissible values: "php", "nvp"');

        $sources[] = __DIR__ . '/fixtures/invalid_config.yml';

        $this->assertProcessedConfigurationEquals([], $sources);
    }
}
