<?php

declare(strict_types=1);

use Brille24\Core\Command\DetermineOrderItemIdByDivaProductionIdCommand;
use Brille24\SyliusTierPricePlugin\Factory\TierPriceFactoryInterface;
use Brille24\SyliusTierPricePlugin\Menu\AdminProductFormMenuListener;
use Brille24\SyliusTierPricePlugin\Menu\AdminProductVariantFormMenuListener;
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
            service(ProductVariantPricesCalculatorInterface::class.'.inner'),
        ])
    ;

    $services->set(OrderPricesRecalculator::class)
        ->decorate('sylius.order_processing.order_prices_recalculator')
    ;

    $services->load('Brille24\\SyliusTierPricePlugin\\Menu\\', __DIR__.'/../../Menu/');

    $services->set(TierPriceFactoryInterface::class, TierPriceFactory::class)
        ->decorate('brille24.factory.tierprice')
        ->args([service(TierPriceFactoryInterface::class.'.inner')])
    ;

    $services->load('Brille24\\SyliusTierPricePlugin\\Fixtures\\', __DIR__.'/../../Fixtures/');
};

