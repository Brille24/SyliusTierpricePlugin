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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\ChannelInterface;

class TierPriceFinderTest extends TestCase
{
    private TierPriceFinder $tierPriceFinder;

    private ChannelInterface $testChannel;

    /** @var TierPriceRepositoryInterface|MockObject<TierPriceRepositoryInterface> */
    private $tierPriceRepo;

    public function setUp(): void
    {
        $this->tierPriceRepo = $this->createMock(TierPriceRepositoryInterface::class);
        $this->tierPriceFinder = new TierPriceFinder($this->tierPriceRepo);

        $this->testChannel = $this->createMock(ChannelInterface::class);
    }

    public function testCalculateWithNotEnoughQuantity(): void
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
        self::assertEquals(null, $tierPriceFound);
    }

    public function testCalculateWithOneTierPrice(): void
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
        self::assertEquals($tierPriceFound, $tierPrice);
    }

    public function testCalculateWithHighestTierPrice(): void
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
        self::assertEquals($tierPrice2, $tierPriceFound);
    }
}
