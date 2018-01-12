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

namespace Brille24\TierPriceBundle\Repository;


use Brille24\TierPriceBundle\Entity\ProductVariant;
use Brille24\TierPriceBundle\Entity\TierPriceInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\ChannelInterface;

class TierPriceRepository extends EntityRepository implements ObjectRepository
{

    /**
     * Gets all tier prices for a product variant for a channel with quantity in ascending order
     *
     * @param ProductVariant   $productVariant
     * @param ChannelInterface $channel
     *
     * @return TierPriceInterface[]
     */
    public function getSortedTierPrices(ProductVariant $productVariant, ChannelInterface $channel): array
    {
        return $this->findBy(['productVariant' => $productVariant, 'channel' => $channel], ['qty' => 'ASC']);
    }
}