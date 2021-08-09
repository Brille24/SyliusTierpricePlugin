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

namespace Brille24\SyliusTierPricePlugin\Entity;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Customer\Model\CustomerGroupInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Class ProductVariant
 *
 * Entity that stores a possible tier pricing for a product
 */
interface TierPriceInterface extends ResourceInterface
{
    public function getPrice(): int;

    public function setPrice(int $price): void;

    public function getQty(): int;

    /**
     * This function does not allow negative values
     */
    public function setQty(int $qty): void;

    public function getProductVariant(): ProductVariantInterface;

    public function setProductVariant(ProductVariantInterface $productVariant): void;

    public function getChannel(): ?ChannelInterface;

    public function setChannel(?ChannelInterface $channel): void;

    public function getCustomerGroup(): ?CustomerGroupInterface;

    public function setCustomerGroup(?CustomerGroupInterface $customerGroup): void;
}
