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

namespace Brille24\TierPriceBundle\Services;

use Brille24\TierPriceBundle\Entity\TierPrice;
use Brille24\TierPriceBundle\Entity\TierPriceInterface;
use Brille24\TierPriceBundle\Traits\TierPriceableInterface;
use Sylius\Component\Core\Model\ChannelInterface;

/**
 * Class TierPriceFinder
 *
 * Finds the cheapest tier price with a given channel
 *
 * @package Brille24\TierPriceBundle\Services
 */
class TierPriceFinder implements TierPriceFinderInterface
{
    /**
     * Finds the cheapest tier price with the matching channel
     *
     * @param TierPriceableInterface $tierPriceableEntity
     * @param ChannelInterface       $channel
     * @param int                    $quantity
     *
     * @return TierPriceInterface|null
     */
    public function find(
        TierPriceableInterface $tierPriceableEntity,
        ChannelInterface $channel,
        int $quantity
    ): ?TierPriceInterface {

        /** @var TierPrice[] $tierPricesForChannel */
        $tierPricesForChannel = $tierPriceableEntity->getTierPricesForChannel($channel);
        dump($tierPricesForChannel);

        // Filters out all tier prices with amounts lower than purchased
        $tierPricesWithQuantityMatching = array_filter($tierPricesForChannel,
            function (TierPriceInterface $tierPrice) use ($quantity) {
                return $tierPrice->getQty() <= $quantity;
            });

        // Gets the cheapest one
        $cheapestTierPrice = array_reduce($tierPricesWithQuantityMatching,
            function (?TierPriceInterface $best, ?TierPriceInterface $tierPrice) {
                $previous = $best ?: $tierPrice;
                return $tierPrice->getPrice() < $previous->getPrice() ? $tierPrice : $previous;
            }, null);

        return $cheapestTierPrice;
    }
}
