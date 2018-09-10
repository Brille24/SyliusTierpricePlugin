<?php

declare(strict_types = 1);

namespace Brille24\SyliusTierPricePlugin\Entity;

use Brille24\SyliusTierPricePlugin\Traits\TierPriceableInterface;

/**
 * Interface ProductVariantInterface
 *
 * @package Brille24\SyliusTierPricePlugin\Entity
 */
interface ProductVariantInterface extends TierPriceableInterface
{
    
    /**
     * @param TierPrice $tierPrice
     */
    public function removeTierPrice(TierPrice $tierPrice): void;
    
    /**
     * @param TierPrice $tierPrice
     */
    public function addTierPrice(TierPrice $tierPrice): void;
}
