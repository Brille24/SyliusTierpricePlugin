<?php

namespace Brille24\TierPriceBundle\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Brille24\TierPriceBundle\Entity\TierPrice;

/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 29/12/17
 * Time: 16:35
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
