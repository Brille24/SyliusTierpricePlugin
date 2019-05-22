<?php

declare(strict_types=1);

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
