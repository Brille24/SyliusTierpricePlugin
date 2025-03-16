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

namespace Brille24\SyliusTierPricePlugin\Services;

use Brille24\SyliusTierPricePlugin\Entity\TierPriceInterface;
use Brille24\SyliusTierPricePlugin\Repository\TierPriceRepositoryInterface;
use Brille24\SyliusTierPricePlugin\Traits\TierPriceableInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Customer\Model\CustomerInterface;

class TierPriceFinder implements TierPriceFinderInterface
{
    public function __construct(private TierPriceRepositoryInterface $tierPriceRepository)
    {
    }

    /**
     * Finds the cheapest tier price with the matching channel
     */
    public function find(
        TierPriceableInterface $tierPriceableEntity,
        ChannelInterface $channel,
        int $quantity,
        ?CustomerInterface $customer = null,
    ): ?TierPriceInterface {
        $group = null;
        if ($customer instanceof CustomerInterface) {
            $group = $customer->getGroup();
        }
        $possibleTierPrices = $this->tierPriceRepository->getSortedTierPrices($tierPriceableEntity, $channel, $group);

        $cheapestTierPrice = null;
        foreach ($possibleTierPrices as $tierPrice) {
            if ($tierPrice->getQty() > $quantity) {
                break;
            }
            $cheapestTierPrice = $tierPrice;
        }

        return $cheapestTierPrice;
    }
}
