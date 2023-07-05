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

use Brille24\SyliusTierPricePlugin\Factory\TierPriceExampleFactory;
use Brille24\SyliusTierPricePlugin\Factory\TierPriceFactory;
use Brille24\SyliusTierPricePlugin\Factory\TierPriceFactoryInterface;
use Brille24\SyliusTierPricePlugin\Fixtures\TierPriceFixture;
use Brille24\SyliusTierPricePlugin\Repository\TierPriceRepositoryInterface;
use Brille24\SyliusTierPricePlugin\Services\OrderPricesRecalculator;
use Brille24\SyliusTierPricePlugin\Services\ProductVariantPriceCalculator;
use Brille24\SyliusTierPricePlugin\Services\TierPriceFinder;
use Brille24\SyliusTierPricePlugin\Services\TierPriceFinderInterface;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->defaults()->autoconfigure()->autowire();

    $services->alias(TierPriceRepositoryInterface::class, 'brille24.repository.tierprice');

    $services->set(TierPriceFinderInterface::class, TierPriceFinder::class);

    $services->set(ProductVariantPricesCalculatorInterface::class, ProductVariantPriceCalculator::class)
        ->decorate('sylius.calculator.product_variant_price')
        ->args([
            service(ProductVariantPricesCalculatorInterface::class . '.inner'),
        ])
    ;

    $services->set(OrderPricesRecalculator::class)
        ->decorate('sylius.order_processing.order_prices_recalculator')
    ;

    $services->load('Brille24\\SyliusTierPricePlugin\\Menu\\', __DIR__ . '/../../Menu/');

    $services->set(TierPriceFactoryInterface::class, TierPriceFactory::class)
        ->decorate('brille24.factory.tierprice')
        ->args([service(TierPriceFactoryInterface::class . '.inner')])
    ;

    $services->set(\Brille24\SyliusTierPricePlugin\Factory\TierPriceExampleFactory::class);

    $services->set(TierPriceFixture::class)
        ->args([
            service('doctrine.orm.default_entity_manager'),
            service(TierPriceExampleFactory::class),
        ])
        ->tag('sylius_fixtures.fixture')
    ;

    $services->set(\Brille24\SyliusTierPricePlugin\Form\TierPriceType::class);

    $services->set(\Brille24\SyliusTierPricePlugin\Form\Extension\ProductVariantTypeExtension::class)
        ->tag('form.type_extension', ['extended_type' => \Sylius\Bundle\CoreBundle\Form\Type\ProductVariant::class, 'priority' => -5])
    ;

    $services->set(\Brille24\SyliusTierPricePlugin\Validator\TierPriceUniqueValidator::class)
        ->args([service('doctrine')])
        ->tag('validator.constraint_validator', ['alias' => 'brille24.tier_price_validator.unqiue'])
    ;
};
