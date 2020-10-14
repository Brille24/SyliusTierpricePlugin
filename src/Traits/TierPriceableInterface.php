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

namespace Brille24\SyliusTierPricePlugin\Traits;

use Brille24\SyliusTierPricePlugin\Entity\TierPriceInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;

interface TierPriceableInterface
{
    /**
     * Returns all tier prices for a given channel
     *
     * @param ChannelInterface       $channel
     * @param CustomerInterface|null $customer
     *
     * @return TierPriceInterface[]
     */
    public function getTierPricesForChannel(ChannelInterface $channel, ?CustomerInterface $customer = null): array;

    /**
     * Returns all tier prices for a given channel
     *
     * @param string $code
     *
     * @return TierPriceInterface[]
     */
    public function getTierPricesForChannelCode(string $code): array;

    /**
     *Returns the tier prices associated
     *
     * @return TierPriceInterface[]
     */
    public function getTierPrices(): array;

    /**
     * Sets the tier prices from an array
     *
     * @param TierPriceInterface[] $tierPrices
     */
    public function setTierPrices(array $tierPrices): void;
}
