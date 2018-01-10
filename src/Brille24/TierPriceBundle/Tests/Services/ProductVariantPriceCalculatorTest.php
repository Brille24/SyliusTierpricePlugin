<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 08/01/18
 * Time: 11:15
 */

declare(strict_types=1);

namespace Brille24\TierPriceBundle\Tests\Services;

use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Brille24\TierPriceBundle\Entity\ProductVariant;
use Brille24\TierPriceBundle\Entity\TierPrice;
use Brille24\TierPriceBundle\Services\ProductVariantPriceCalculator;

class ProductVariantPriceCalculatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var ProductVariantPriceCalculatorInterface */
    private $basePriceCalculator;

    /** @var ProductVariantPriceCalculatorInterface */
    private $priceCalculator;

    /** @var ChannelInterface */
    private $testChannel;

    public function setup()
    {
        $this->basePriceCalculator = $this->createMock(ProductVariantPriceCalculatorInterface::class);
        $this->basePriceCalculator->method('calculate')->willReturn(-1); // To indicate no tier prices

        $this->priceCalculator = new ProductVariantPriceCalculator($this->basePriceCalculator);

        $this->testChannel = $this->createMock(ChannelInterface::class);
    }

    public function testCalculateWithNoTierPrice()
    {
        ### PREPARE
        $productVariant = $this->createMock(ProductVariant::class);
        $productVariant->method('getTierPrices')->willReturn([]);

        ### EXECUTE
        $price = $this->priceCalculator->calculate(
            $productVariant,
            ['channel' => $this->testChannel, 'quantity' => 10]
        );

        ### CHECK
        $this->assertEquals(-1, $price);
    }

    public function testCalculateWithTierPriceFromDifferentChannel()
    {
        ### PREPARE
        $tierPrice = $this->createMock(TierPrice::class);
        $tierPrice->method('getChannel')->willReturn($this->createMock(ChannelInterface::class));

        $productVariant = $this->createMock(ProductVariant::class);
        $productVariant->method('getTierPrices')->willReturn([$tierPrice]);

        ### EXECUTE
        $price = $this->priceCalculator->calculate(
            $productVariant,
            ['channel' => $this->testChannel, 'quantity' => 10]
        );

        ### CHECK
        $this->assertEquals(-1, $price);
    }

    public function testCalculateWithNoTierpriceAmount()
    {
        ### PREPARE
        $tierPrice = $this->createMock(TierPrice::class);
        $tierPrice->method('getChannel')->willReturn($this->testChannel);
        $tierPrice->method('getPrice')->willReturn(1);
        $tierPrice->method('getQty')->willReturn(100);

        $productVariant = $this->createMock(ProductVariant::class);
        $productVariant->method('getTierPrices')->willReturn([$tierPrice]);

        ### EXECUTE
        $price = $this->priceCalculator->calculate(
            $productVariant,
            ['channel' => $this->testChannel, 'quantity' => 10]
        );

        ### CHECK
        $this->assertEquals(-1, $price);
    }

    public function testCalculateWithOneTierPrice()
    {
        ### PREPARE
        $tierPrice = $this->createMock(TierPrice::class);
        $tierPrice->method('getChannel')->willReturn($this->testChannel);
        $tierPrice->method('getPrice')->willReturn(1);
        $tierPrice->method('getQty')->willReturn(5);

        $productVariant = $this->createMock(ProductVariant::class);
        $productVariant->method('getTierPrices')->willReturn([$tierPrice]);

        ### EXECUTE
        $price = $this->priceCalculator->calculate(
            $productVariant,
            ['channel' => $this->testChannel, 'quantity' => 10]
        );

        ### CHECK
        $this->assertEquals(1, $price);
    }

    public function testCalculateWithHighestTierPrice()
    {
        ### PREPARE
        $tierPrice1 = $this->createMock(TierPrice::class);
        $tierPrice1->method('getChannel')->willReturn($this->testChannel);
        $tierPrice1->method('getPrice')->willReturn(10);
        $tierPrice1->method('getQty')->willReturn(5);

        $tierPrice2 = $this->createMock(TierPrice::class);
        $tierPrice2->method('getChannel')->willReturn($this->testChannel);
        $tierPrice2->method('getPrice')->willReturn(5);
        $tierPrice2->method('getQty')->willReturn(10);

        $productVariant = $this->createMock(ProductVariant::class);
        $productVariant->method('getTierPrices')->willReturn([$tierPrice1, $tierPrice2]);

        ### EXECUTE
        $price = $this->priceCalculator->calculate(
            $productVariant,
            ['channel' => $this->testChannel, 'quantity' => 10]
        );

        ### CHECK
        $this->assertEquals(5, $price);
    }
}
