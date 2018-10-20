<?php

declare(strict_types=1);
/**
 * This file is part of the Brille24 tierprice plugin.
 *
 * (c) Brille24 GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brille24\SyliusTierPricePlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class Brille24SyliusTierPriceExtension extends Extension
{
    /** {@inheritdoc} */
    public function load(array $config, ContainerBuilder $container): void
    {
        $this->processConfiguration($this->getConfiguration([], $container), $config);
        new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
    }
}
