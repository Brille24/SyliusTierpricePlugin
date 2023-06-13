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

namespace Brille24\SyliusTierPricePlugin\Factory;

use Brille24\SyliusTierPricePlugin\Entity\ProductVariantInterface;
use Brille24\SyliusTierPricePlugin\Entity\TierPriceInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Webmozart\Assert\Assert;

final class TierPriceFactory implements TierPriceFactoryInterface
{
    public function __construct(private FactoryInterface $factory)
    {
    }

    /** @inheritdoc */
    public function createNew(): object
    {
        return $this->factory->createNew();
    }

    /** @inheritdoc */
    public function createAtProductVariant(
        ProductVariantInterface $productVariant,
        array $options = [],
    ): TierPriceInterface {
        Assert::integer($options['quantity']);
        Assert::nullOrIsInstanceOf($options['channel'], ChannelInterface::class);
        Assert::integer($options['price']);

        /** @var TierPriceInterface $tierPrice */
        $tierPrice = $this->createNew();

        $tierPrice->setQty($options['quantity']);
        $tierPrice->setChannel($options['channel']);
        $tierPrice->setPrice($options['price']);
        $tierPrice->setProductVariant($productVariant);

        $productVariant->addTierPrice($tierPrice);

        return $tierPrice;
    }
}
