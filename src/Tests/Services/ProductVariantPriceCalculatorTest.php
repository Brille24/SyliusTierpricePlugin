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

namespace Brille24\SyliusTierPricePlugin\Tests\Services;

use Brille24\SyliusTierPricePlugin\Entity\ProductVariant;
use Brille24\SyliusTierPricePlugin\Entity\TierPrice;
use Brille24\SyliusTierPricePlugin\Services\ProductVariantPriceCalculator;
use Brille24\SyliusTierPricePlugin\Services\TierPriceFinderInterface;
use Mockery\Mock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductVariant as SyliusProductVariant;
use Sylius\Component\Customer\Context\CustomerContextInterface;

class ProductVariantPriceCalculatorTest extends TestCase
{
    /** @var ProductVariantPricesCalculatorInterface|MockObject */
    private $basePriceCalculator;

    /** @var ProductVariantPricesCalculatorInterface */
    private $priceCalculator;

    /** @var TierPriceFinderInterface|MockObject */
    private $tierPriceFinder;

    /** @var CustomerContextInterface|MockObject */
    private $customerContext;

    public function setUp(): void
    {
        $this->basePriceCalculator = $this->createMock(ProductVariantPricesCalculatorInterface::class);
        $this->basePriceCalculator->method('calculate')->willReturn(-1); // To indicate no tier prices

        $this->tierPriceFinder = $this->createMock(TierPriceFinderInterface::class);

        $this->customerContext = $this->createMock(CustomerContextInterface::class);

        $this->priceCalculator = new ProductVariantPriceCalculator($this->basePriceCalculator, $this->tierPriceFinder, $this->customerContext);
    }

    public function testCalculateWithNonTierpriceable(): void
    {
        //## PREPARE
        $productVariant = $this->createMock(SyliusProductVariant::class);
        $testChannel    = $this->createMock(ChannelInterface::class);

        //## EXECUTE
        $price = $this->priceCalculator->calculate($productVariant, ['channel' => $testChannel, 'quantity' => 10]);

        //## CHECK
        self::assertEquals(-1, $price);
    }

    public function testCalculatePriceWithEmptyTierPrices(): void
    {
        //## PREPARE
        $productVariant = $this->createMock(ProductVariant::class);
        $testChannel    = $this->createMock(ChannelInterface::class);

        $this->tierPriceFinder->method('find')->willReturn(null);

        //## EXECUTE
        $result = $this->priceCalculator->calculate($productVariant, ['channel' => $testChannel, 'quantity' => 10]);

        //## CHECK
        self::assertEquals(-1, $result);
    }

    public function testCalculatePriceWithTierPrices(): void
    {
        //## PREPARE
        $productVariant = $this->createMock(ProductVariant::class);
        $testChannel    = $this->createMock(ChannelInterface::class);

        $this->tierPriceFinder->method('find')->willReturn(new TierPrice(2, 2));

        //## EXECUTE
        $result = $this->priceCalculator->calculate($productVariant, ['channel' => $testChannel, 'quantity' => 10]);

        //## CHECK
        self::assertEquals(2, $result);
    }
}
