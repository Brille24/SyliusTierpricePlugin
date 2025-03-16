<?php

/**
 * This file is part of the Brille24 tierprice plugin.
 *
 * (c) Mamazu
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Repository;

use Brille24\SyliusTierPricePlugin\Entity\TierPriceInterface;
use Brille24\SyliusTierPricePlugin\Traits\TierPriceableInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Customer\Model\CustomerGroupInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface TierPriceRepositoryInterface extends RepositoryInterface
{
    /**
     * Gets all tier prices for a product variant for a channel and optionally customer group with quantity in ascending order
     *
     * @return TierPriceInterface[]
     */
    public function getSortedTierPrices(TierPriceableInterface $productVariant, ChannelInterface $channel, ?CustomerGroupInterface $customerGroup = null): array;

    /**
     * Gets a tierprice by product variant and quantity
     */
    public function getTierPriceForQuantity(TierPriceableInterface $productVariant, ChannelInterface $channel, ?CustomerGroupInterface $customerGroup, int $quantity): ?TierPriceInterface;
}
