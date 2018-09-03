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

namespace Brille24\SyliusTierPricePlugin\Entity;

use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface TierPriceInterface extends ResourceInterface
{
    /**
     * @return int
     */
    public function getPrice(): int;

    /**
     * @param int $price
     */
    public function setPrice(int $price): void;

    /**
     * @return int
     */
    public function getQty(): int;

    /**
     * This function does not allow negative values
     *
     * @param int $qty
     */
    public function setQty(int $qty): void;

    /**
     * @return ProductVariantInterface
     */
    public function getProductVariant(): ProductVariantInterface;

    /**
     * @param ProductVariantInterface $productVariants
     */
    public function setProductVariant(ProductVariantInterface $productVariants): void;

    /**
     * @return ChannelInterface|null
     */
    public function getChannel(): ?ChannelInterface;

    /**
     * @param ChannelInterface|null $channel
     */
    public function setChannel(?ChannelInterface $channel): void;
}
