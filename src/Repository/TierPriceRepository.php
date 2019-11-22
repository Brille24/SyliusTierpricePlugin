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

namespace Brille24\SyliusTierPricePlugin\Repository;

use Brille24\SyliusTierPricePlugin\Entity\TierPriceInterface;
use Brille24\SyliusTierPricePlugin\Traits\TierPriceableInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Customer\Model\CustomerGroupInterface;

class TierPriceRepository extends EntityRepository implements TierPriceRepositoryInterface
{
    /** {@inheritdoc}
     *
     * @return TierPriceInterface[]
     */
    public function getSortedTierPrices(TierPriceableInterface $productVariant, ChannelInterface $channel, ?CustomerGroupInterface $customerGroup = null): array
    {
        /*
         * If we have a customer group, check for tier prices for that customer group, if we find any return them and
         * only them.
         */
        if ($customerGroup instanceof CustomerGroupInterface) {
            $prices = $this->findBy(['productVariant' => $productVariant, 'channel' => $channel, 'customerGroup' => $customerGroup], ['qty' => 'ASC']);
            if (count($prices) > 0) {
                return $prices;
            }
        }

        /*
         * If we don't have a customer group or the customer group has no tier prices get the tier prices with
         * no group set.
         */
        return $this->findBy(['productVariant' => $productVariant, 'channel' => $channel, 'customerGroup' => null], ['qty' => 'ASC']);
    }

    /**
     * {@inheritdoc}
     */
    public function getTierPriceForQuantity(TierPriceableInterface $productVariant, ChannelInterface $channel, ?CustomerGroupInterface $customerGroup, int $quantity): ?TierPriceInterface
    {
        return $this->findOneBy([
            'productVariant' => $productVariant,
            'channel'        => $channel,
            'customerGroup'  => $customerGroup,
            'qty'            => $quantity,
        ]);
    }
}
