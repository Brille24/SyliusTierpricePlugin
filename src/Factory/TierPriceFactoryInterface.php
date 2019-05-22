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

namespace Brille24\SyliusTierPricePlugin\Factory;

use Brille24\SyliusTierPricePlugin\Entity\ProductVariantInterface;
use Brille24\SyliusTierPricePlugin\Entity\TierPriceInterface;
use Sylius\Component\Core\Model\ChannelInterface;

interface TierPriceFactoryInterface
{
    /**
     * Creates a Tier price entity
     *
     * @param int              $quantity
     * @param ChannelInterface $channel
     * @param int              $price
     *
     * @return TierPriceInterface
     */
    public function createNew(int $quantity, ChannelInterface $channel, int $price): TierPriceInterface;

    /**
     * Creates a tierprice entity which is attached to a product variant
     *
     * @param ProductVariantInterface $productVariant
     * @param array                   $options
     *
     * @return TierPriceInterface
     */
    public function createForProductVariant(ProductVariantInterface $productVariant, array $options = []
    ): TierPriceInterface;
}
