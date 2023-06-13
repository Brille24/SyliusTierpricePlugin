<?php

/**
 * This file is part of the Brille24 tierprice plugin.
 *
 * (c) Brille24 GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Traits;

use Brille24\SyliusTierPricePlugin\Entity\ProductVariant;
use Brille24\SyliusTierPricePlugin\Entity\TierPriceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Customer\Model\CustomerGroupInterface;

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

    /** @var ArrayCollection<int, TierPriceInterface> */
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
     *
     * @return TierPriceInterface[]
     */
    public function getTierPricesForChannel(ChannelInterface $channel, ?CustomerInterface $customer = null): array
    {
        $channelTierPrices = array_filter($this->getTierPrices(), function (TierPriceInterface $tierPrice) use ($channel): bool {
            $tierPriceChannel = $tierPrice->getChannel();

            return $tierPriceChannel !== null && $tierPriceChannel->getId() === $channel->getId();
        });

        return $this->filterPricesWithCustomerGroup($channelTierPrices, $customer);
    }

    /**
     * Returns the tier prices only for one channel
     *
     *
     * @return TierPriceInterface[]
     */
    public function getTierPricesForChannelCode(string $code, ?CustomerInterface $customer = null): array
    {
        $channelTierPrices = array_filter($this->getTierPrices(), function (TierPriceInterface $tierPrice) use ($code): bool {
            $tierPriceChannel = $tierPrice->getChannel();

            return $tierPriceChannel !== null && $tierPriceChannel->getCode() === $code;
        });

        return $this->filterPricesWithCustomerGroup($channelTierPrices, $customer);
    }

    /**
     * Removes a tier price from the array collection
     */
    public function removeTierPrice(TierPriceInterface $tierPrice): void
    {
        $this->tierPrices->removeElement($tierPrice);
    }

    /**
     * Adds an element to the list
     */
    public function addTierPrice(TierPriceInterface $tierPrice): void
    {
        $tierPrice->setProductVariant($this);
        $this->tierPrices->add($tierPrice);
    }

    /**
     * Sets the tier prices form the array collection
     *
     * @param TierPriceInterface[] $tierPrices
     */
    public function setTierPrices(array $tierPrices): void
    {
        if (!$this instanceof ProductVariantInterface) {
            return;
        }

        $this->tierPrices = new ArrayCollection();

        foreach ($tierPrices as $tierPrice) {
            $this->addTierPrice($tierPrice);
        }
    }

    /**
     * @param TierPriceInterface[] $tierPrices
     *
     * @return TierPriceInterface[]
     */
    private function filterPricesWithCustomerGroup(array $tierPrices, ?CustomerInterface $customer = null): array
    {
        $group = null;
        if ($customer instanceof CustomerInterface) {
            $group = $customer->getGroup();
        }

        // Check if there are any tier prices specifically for the passed customer's group
        $hasGroupPrice = false;
        if ($group instanceof CustomerGroupInterface) {
            foreach ($tierPrices as $tierPrice) {
                /** @psalm-suppress PossiblyNullReference */
                if (
                    $tierPrice->getCustomerGroup() instanceof CustomerGroupInterface &&
                    $tierPrice->getCustomerGroup()->getId() === $group->getId()
                ) {
                    $hasGroupPrice = true;

                    break;
                }
            }
        }

        if (!$group instanceof CustomerGroupInterface || !$hasGroupPrice) {
            /*
             * We either have no CustomerGroup or there are no tier prices for the specified group so only return
             * tier prices with no customer group set
             */
            return array_filter($tierPrices, static fn(TierPriceInterface $tierPrice): bool => $tierPrice->getCustomerGroup() === null);
        }

        /*
         * We have a customer group and $tierPrices contains tier prices for that specific group so only return
         * tier prices for that group
         */
        return array_filter($tierPrices, static fn(TierPriceInterface $tierPrice): bool => /** @psalm-suppress PossiblyNullReference */
$tierPrice->getCustomerGroup() !== null &&
        $tierPrice->getCustomerGroup()->getId() === $group->getId());
    }
}
