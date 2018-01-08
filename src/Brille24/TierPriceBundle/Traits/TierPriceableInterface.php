<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 04/01/18
 * Time: 10:23
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
