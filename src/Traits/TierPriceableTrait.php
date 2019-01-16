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

use Brille24\SyliusTierPricePlugin\Entity\ProductVariant;
use Brille24\SyliusTierPricePlugin\Entity\TierPrice;
use Brille24\SyliusTierPricePlugin\Entity\TierPriceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

/**
 * Trait TierPriceableTrait
 *
 * Trait that implements the tierpricing functionality.
 * Used in:
 * <li>@see ProductVariant</li>
 */
trait TierPriceableTrait
{
    public function initTierPriceableTrait(): void
    {
        $this->tierPrices = new ArrayCollection();
    }

    /** @var ArrayCollection */
    protected $tierPrices;

    /**
     * Returns all tier prices for this product variant.
     *
     * @return TierPriceInterface[]
     */
    public function getTierPrices(): array
    {
        return $this->tierPrices->toArray();
    }

    /**
     * Returns the tier prices only for one channel
     *
     * @param ChannelInterface $channel
     *
     * @return TierPriceInterface[]
     */
    public function getTierPricesForChannel(ChannelInterface $channel): array
    {
        return array_filter($this->getTierPrices(), function (TierPrice $tierPrice) use ($channel) {
            $tierPriceChannel = $tierPrice->getChannel();

            return $tierPriceChannel === null ? false : $tierPriceChannel->getId() === $channel->getId();
        });
    }

    /**
     * Returns the tier prices only for one channel
     *
     * @param string $code
     *
     * @return TierPriceInterface[]
     */
    public function getTierPricesForChannelCode(string $code): array
    {
        return array_filter($this->getTierPrices(), function (TierPrice $tierPrice) use ($code) {
            $tierPriceChannel = $tierPrice->getChannel();

            return $tierPriceChannel === null ? false : $tierPriceChannel->getCode() === $code;
        });
    }

    /**
     * Removes a tier price from the array collection
     *
     * @param TierPrice $tierPrice
     */
    public function removeTierPrice(TierPrice $tierPrice): void
    {
        $this->tierPrices->removeElement($tierPrice);
    }

    /**
     * Adds an element to the list
     *
     * @param TierPrice $tierPrice
     */
    public function addTierPrice(TierPrice $tierPrice): void
    {
        $tierPrice->setProductVariant($this);
        $this->tierPrices->add($tierPrice);
    }

    /**
     * Sets the tier prices form the array collection
     *
     * @param array $tierPrices
     */
    public function setTierPrices(array $tierPrices): void
    {
        if (!$this instanceof ProductVariantInterface) {
            return;
        }

        $this->tierPrices = new ArrayCollection();

        foreach ($tierPrices as $tierPrice) {
            /** @var TierPrice $tierPrice */
            $this->addTierPrice($tierPrice);
        }
    }
}
