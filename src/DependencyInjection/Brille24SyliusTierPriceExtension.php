<?php

/**
 * This file is part of the Brille24 tierprice plugin.
 *
 * (c) Brille24 GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class Brille24SyliusTierPriceExtension extends Extension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    /** @inheritdoc */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $this->processConfiguration($this->getConfiguration([], $container), $configs);
        new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loder = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loder->load('services.php');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $this->prependDoctrineMigrations($container);

        $container->prependExtensionConfig(
            'sylius_ui',
            [
                'events' => [
                    'sylius.admin.product_variant.update.javascripts' => [
                        'blocks' => [
                            'brille24_tierprice_plugin_product_variant' => [
                                'template' => '@Brille24SyliusTierPricePlugin/Admin/_javascripts.html.twig'
                            ]
                        ],
                    ],
                    'sylius.admin.product.update.javascripts' => [
                        'blocks' => [
                            'brille24_tierprice_plugin_product_variant' => [
                                'template' => '@Brille24SyliusTierPricePlugin/Admin/_javascripts.html.twig',
                            ]
                        ]
                    ],
                    'sylius.shop.layout.javascripts' => [
                        'blocks' => [
                            'brille24_tierprice_plugin_add_tierpricing_calculation_to_shop' => [
                                'template' => '@Brille24SyliusTierPricePlugin/Shop/_javascripts.html.twig',
                            ]
                        ]
                    ],
                    'sylius.shop.product.show.before_add_to_cart' => [
                        'blocks' => [
                            'brille24_tierprice_plugin_add_tierprice_table_to_product_view' => [
                                'template' => '@Brille24SyliusTierPricePlugin/Shop/Product/Show/_tier_price_promo.html.twig',
                            ]
                        ]
                    ]
                ]
            ]
        );

    }

     protected function getMigrationsNamespace(): string
     {
         return 'Brille24\SyliusTierPricePlugin\Migrations';
     }

    protected function getMigrationsDirectory(): string
    {
        return '@Brille24SyliusTierPricePlugin/Migrations';
    }

    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return ['Sylius\Bundle\CoreBundle\Migrations'];
    }

    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return new Configuration();
    }
}
