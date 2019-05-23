<?php
/**
 * This file is part of the Brille24 tierprice plugin.
 *
 * (c) Brille24 GmbH
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Factory;

use Brille24\SyliusTierPricePlugin\Entity\ProductVariantInterface;
use Brille24\SyliusTierPricePlugin\Entity\TierPrice;
use Brille24\SyliusTierPricePlugin\Entity\TierPriceInterface;
use Sylius\Component\Core\Model\ChannelInterface;

final class TierPriceFactory implements TierPriceFactoryInterface
{
    public function createNew(int $quantity, ChannelInterface $channel, int $price): TierPriceInterface
    {
        $tierPrice = new TierPrice($quantity, $price);
        $tierPrice->setChannel($channel);

        return $tierPrice;
    }

    /** {@inheritdoc} */
    public function createAtProductVariant(
        ProductVariantInterface $productVariant,
        array $options = []
    ): TierPriceInterface {
        $tierPrice = $this->createNew(
            $options['quantity'],
            $options['channel'],
            $options['price']
        );

        $tierPrice->setProductVariant($productVariant);
        $productVariant->addTierPrice($tierPrice);

        return $tierPrice;
    }
}
