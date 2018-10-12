<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 08/01/18
 * Time: 11:15
 */
declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Tests\Services;

use Brille24\SyliusTierPricePlugin\Entity\ProductVariant;
use Brille24\SyliusTierPricePlugin\Entity\TierPrice;
use Brille24\SyliusTierPricePlugin\Services\ProductVariantPriceCalculator;
use Brille24\SyliusTierPricePlugin\Services\TierPriceFinderInterface;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductVariant as SyliusProductVariant;

class ProductVariantPriceCalculatorTest extends \PHPUnit\Framework\TestCase
{
    /** @var ProductVariantPriceCalculatorInterface */
    private $basePriceCalculator;

    /** @var ProductVariantPriceCalculatorInterface */
    private $priceCalculator;

    /** @var TierPriceFinderInterface */
    private $tierPriceFinder;

    public function setup()
    {
        $this->basePriceCalculator = $this->createMock(ProductVariantPriceCalculatorInterface::class);
        $this->basePriceCalculator->method('calculate')->willReturn(-1); // To indicate no tier prices

        $this->tierPriceFinder = $this->createMock(TierPriceFinderInterface::class);

        $this->priceCalculator = new ProductVariantPriceCalculator($this->basePriceCalculator, $this->tierPriceFinder);
    }

    public function testCalculateWithNonTierpriceable()
    {
        //## PREPARE
        $productVariant = $this->createMock(SyliusProductVariant::class);
        $testChannel    = $this->createMock(ChannelInterface::class);

        //## EXECUTE
        $price = $this->priceCalculator->calculate($productVariant, ['channel' => $testChannel, 'quantity' => 10]);

        //## CHECK
        $this->assertEquals(-1, $price);
    }

    public function testCalculatePriceWithEmptyTierPrices()
    {
        //## PREPARE
        $productVariant = $this->createMock(ProductVariant::class);
        $testChannel    = $this->createMock(ChannelInterface::class);

        $this->tierPriceFinder->method('find')->willReturn(null);

        //## EXECUTE
        $result = $this->priceCalculator->calculate($productVariant, ['channel' => $testChannel, 'quantity' => 10]);

        //## CHECK
        $this->assertEquals(-1, $result);
    }

    public function testCalculatePriceWithTierPrices()
    {
        //## PREPARE
        $productVariant = $this->createMock(ProductVariant::class);
        $testChannel    = $this->createMock(ChannelInterface::class);

        $this->tierPriceFinder->method('find')->willReturn(new TierPrice(2, 2));

        //## EXECUTE
        $result = $this->priceCalculator->calculate($productVariant, ['channel' => $testChannel, 'quantity' => 10]);

        //## CHECK
        $this->assertEquals(2, $result);
    }
}
