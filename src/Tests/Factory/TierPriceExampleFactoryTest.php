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

namespace Brille24\SyliusTierPricePlugin\Tests\Factory;

use Brille24\SyliusTierPricePlugin\Entity\ProductVariantInterface;
use Brille24\SyliusTierPricePlugin\Entity\TierPrice;
use Brille24\SyliusTierPricePlugin\Factory\TierPriceExampleFactory;
use Brille24\SyliusTierPricePlugin\Factory\TierPriceFactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface;

class TierPriceExampleFactoryTest extends TestCase
{
    /**
     * @var TierPriceExampleFactory
     */
    private $subject;

    /**
     * @var MockObject|ProductVariantRepositoryInterface
     */
    private $productVariantRepository;

    /**
     * @var MockObject|ChannelRepositoryInterface
     */
    private $channelRepository;

    /**
     * @var TierPriceFactoryInterface|MockObject
     */
    private $tierPriceFactory;

    protected function setUp(): void
    {
        $this->productVariantRepository = $this->createMock(ProductVariantRepositoryInterface::class);
        $this->channelRepository        = $this->createMock(ChannelRepositoryInterface::class);
        $this->tierPriceFactory         = $this->createMock(TierPriceFactoryInterface::class);

        $this->subject = new TierPriceExampleFactory($this->productVariantRepository, $this->channelRepository, $this->tierPriceFactory);
    }

    public function test_create(): void
    {
        $tierPrice      = $this->createMock(TierPrice::class);
        $productVariant = $this->createMock(ProductVariantInterface::class);
        $channel        = $this->createMock(ChannelInterface::class);

        $options = [
            'quantity'        => 10,
            'price'           => 100,
            'product_variant' => $productVariant,
            'channel'         => $channel,
        ];

        $this->tierPriceFactory
            ->method('createAtProductVariant')
            ->with($productVariant, $options)
            ->willReturn($tierPrice);

        $this->assertSame($tierPrice, $this->subject->create($options));
    }
}
