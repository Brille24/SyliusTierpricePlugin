<?php
/**
 * This file is part of the Brille24 tierprice plugin.
 *
 * (c) Brille24 GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Tests\Services;

use Brille24\SyliusTierPricePlugin\Entity\ProductVariant;
use Brille24\SyliusTierPricePlugin\Entity\TierPrice;
use Brille24\SyliusTierPricePlugin\Repository\TierPriceRepositoryInterface;
use Brille24\SyliusTierPricePlugin\Services\TierPriceFinder;
use Sylius\Component\Core\Model\ChannelInterface;

class TierPriceFinderTest extends \PHPUnit\Framework\TestCase
{
    /** @var TierPriceFinder */
    private $tierPriceFinder;

    /** @var ChannelInterface */
    private $testChannel;

    /** @var TierPriceRepositoryInterface */
    private $tierPriceRepo;

    public function __construct(
        ?string $name = null,
        array $data = [],
        string $dataName = ''
    ) {
        parent::__construct($name, $data, $dataName);

        $this->tierPriceRepo   = $this->createMock(TierPriceRepositoryInterface::class);
        $this->tierPriceFinder = new TierPriceFinder($this->tierPriceRepo);

        $this->testChannel = $this->createMock(ChannelInterface::class);
    }

    public function testCalculateWithNotEnoughQuantity()
    {
        //## PREPARE
        $tierPrice = $this->createMock(TierPrice::class);
        $tierPrice->method('getPrice')->willReturn(1);
        $tierPrice->method('getQty')->willReturn(20);

        $productVariant = $this->createMock(ProductVariant::class);
        $this->tierPriceRepo->method('getSortedTierPrices')->willReturn([$tierPrice]);

        //## EXECUTE
        $tierPriceFound = $this->tierPriceFinder->find($productVariant, $this->testChannel, 10);

        //## CHECK
        $this->assertEquals(null, $tierPriceFound);
    }

    public function testCalculateWithOneTierPrice()
    {
        //## PREPARE
        $tierPrice = $this->createMock(TierPrice::class);
        $tierPrice->method('getPrice')->willReturn(1);
        $tierPrice->method('getQty')->willReturn(5);

        $productVariant = $this->createMock(ProductVariant::class);
        $this->tierPriceRepo->method('getSortedTierPrices')->willReturn([$tierPrice]);

        //## EXECUTE
        $tierPriceFound = $this->tierPriceFinder->find($productVariant, $this->testChannel, 10);

        //## CHECK
        $this->assertEquals($tierPriceFound, $tierPrice);
    }

    public function testCalculateWithHighestTierPrice()
    {
        //## PREPARE
        $tierPrice1 = $this->createMock(TierPrice::class);
        $tierPrice1->method('getPrice')->willReturn(500);
        $tierPrice1->method('getQty')->willReturn(10);

        $tierPrice2 = $this->createMock(TierPrice::class);
        $tierPrice2->method('getPrice')->willReturn(10);
        $tierPrice2->method('getQty')->willReturn(50);

        $productVariant = $this->createMock(ProductVariant::class);
        $this->tierPriceRepo->method('getSortedTierPrices')->willReturn([$tierPrice1, $tierPrice2]);

        //## EXECUTE
        $tierPriceFound = $this->tierPriceFinder->find($productVariant, $this->testChannel, 11);

        //## CHECK
        $this->assertEquals($tierPrice2, $tierPriceFound);
    }
}
