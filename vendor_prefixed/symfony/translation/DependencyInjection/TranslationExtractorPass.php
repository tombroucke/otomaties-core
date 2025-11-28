<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace OtomatiesCoreVendor\Symfony\Component\Translation\DependencyInjection;

use OtomatiesCoreVendor\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use OtomatiesCoreVendor\Symfony\Component\DependencyInjection\ContainerBuilder;
use OtomatiesCoreVendor\Symfony\Component\DependencyInjection\Reference;
/**
 * Adds tagged translation.extractor services to translation extractor.
 */
class TranslationExtractorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('translation.extractor')) {
            return;
        }
        $definition = $container->getDefinition('translation.extractor');
        foreach ($container->findTaggedServiceIds('translation.extractor', \true) as $id => $attributes) {
            $definition->addMethodCall('addExtractor', [$attributes[0]['alias'] ?? $id, new Reference($id)]);
        }
    }
}
