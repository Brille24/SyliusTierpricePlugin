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

use Brille24\TierPriceBundle\Entity\ProductVariant;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Brille24\TierPriceBundle\Entity\TierPrice;

/**
 * Trait TierPriceableTrait
 *
 * Trait that implements the tierpricing functionality.
 * Used in:
 * <li>@see ProductVariant</li>
 *
 * @package Brille24\TierPriceBundle\Traits
 */
trait TierPriceableTrait
{

    /** @var Collection */
    protected $tierPrices;

    public function getTierPrices(): array
    {
        $this->tierPrices = $this->tierPrices ?: new ArrayCollection();

        return $this->tierPrices->toArray();
    }

    public function getTierPricesForChannel(ChannelInterface $channel): array
    {
        return array_filter($this->getTierPrices(), function (TierPrice $tierPrice) use ($channel){
            return $tierPrice->getChannel()->getId() === $channel->getId();
        });
    }

    public function removeTierPrice(TierPrice $tierPrice): void
    {
        $this->tierPrices->removeElement($tierPrice);
    }

    public function addTierPrice(TierPrice $tierPrice): void
    {
        $tierPrice->setProductVariant($this);
        $this->tierPrices->add($tierPrice);
    }

    public function setTierPrices(array $tierPrices): void
    {
        if ($this instanceof ProductVariantInterface) {
            $this->tierPrices = new ArrayCollection();

            foreach ($tierPrices as $tierPrice) {
                /** @var TierPrice $tierPrice */
                $this->addTierPrice($tierPrice);
            }
        }
    }

}
