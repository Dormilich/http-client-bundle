<?php

namespace Dormilich\Bundle\HttpClientBundle;

use Dormilich\Bundle\HttpClientBundle\DependencyInjection\Compiler\DecoderCompilerPass;
use Dormilich\Bundle\HttpClientBundle\DependencyInjection\Compiler\EncoderCompilerPass;
use Dormilich\Bundle\HttpClientBundle\DependencyInjection\Compiler\TransformerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DormilichHttpClientBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DecoderCompilerPass());
        $container->addCompilerPass(new EncoderCompilerPass());
        $container->addCompilerPass(new TransformerCompilerPass());
    }
}
