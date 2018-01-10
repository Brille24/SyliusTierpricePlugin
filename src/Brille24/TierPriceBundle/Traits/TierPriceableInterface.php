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

namespace Brille24\TierPriceBundle\Traits;


use Brille24\TierPriceBundle\Entity\TierPrice;

interface TierPriceableInterface
{
    /**
     *Returns the tier prices associated
     *
     * @return TierPrice[]
     */
    public function getTierPrices(): array;

    /**
     * Sets the tier prices from an array
     *
     * @param TierPrice[] $tierPrices
     */
    public function setTierPrices(array $tierPrices): void;
}
