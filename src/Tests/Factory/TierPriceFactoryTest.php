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

namespace Brille24\SyliusTierPricePlugin\Tests\Factory;

use Brille24\SyliusTierPricePlugin\Entity\ProductVariantInterface;
use Brille24\SyliusTierPricePlugin\Entity\TierPrice;
use Brille24\SyliusTierPricePlugin\Factory\TierPriceFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class TierPriceFactoryTest extends TestCase
{
    /**
     * @var TierPriceFactory
     */
    private $subject;

    /**
     * @var MockObject|FactoryInterface
     */
    private $baseFactory;

    public function setup(): void
    {
        $this->baseFactory = $this->createMock(FactoryInterface::class);

        $this->subject = new TierPriceFactory($this->baseFactory);
    }

    public function test_createNew(): void
    {
        $tierPrice = $this->createMock(TierPrice::class);

        $this->baseFactory->method('createNew')->willReturn($tierPrice);

        $this->assertSame($tierPrice, $this->subject->createNew());
    }

    public function test_createAtProductVariant(): void
    {
        $channel        = $this->createMock(ChannelInterface::class);
        $productVariant = $this->createMock(ProductVariantInterface::class);

        $tierPrice = $this->createMock(TierPrice::class);
        $tierPrice->expects($this->once())->method('setQty')->with(10);
        $tierPrice->expects($this->once())->method('setChannel')->with($channel);
        $tierPrice->expects($this->once())->method('setPrice')->with(100);
        $tierPrice->expects($this->once())->method('setProductVariant')->with($productVariant);

        $productVariant->method('addTierPrice')->with($tierPrice);
        $this->baseFactory->expects($this->once())->method('createNew')->willReturn($tierPrice);

        $this->subject->createAtProductVariant($productVariant, [
            'quantity' => 10,
            'channel'  => $channel,
            'price'    => 100,
        ]);
    }
}
