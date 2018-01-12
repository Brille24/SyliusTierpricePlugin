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

namespace Brille24\TierPriceBundle\Services;

use Brille24\TierPriceBundle\Entity\ProductVariant;
use Brille24\TierPriceBundle\Entity\TierPriceInterface;
use Brille24\TierPriceBundle\Repository\TierPriceRepository;
use Brille24\TierPriceBundle\Traits\TierPriceableInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Webmozart\Assert\Assert;

/**
 * Class TierPriceFinder
 *
 * Finds the cheapest tier price with a given channel
 *
 * @package Brille24\TierPriceBundle\Services
 */
class TierPriceFinder implements TierPriceFinderInterface
{
    /** @var TierPriceRepository */
    private $tierPriceRepository;

    public function __construct(TierPriceRepository $tierPriceRepository)
    {
        $this->tierPriceRepository = $tierPriceRepository;
    }

    /**
     * Finds the cheapest tier price with the matching channel
     *
     * @param TierPriceableInterface $tierPriceableEntity
     * @param ChannelInterface       $channel
     * @param int                    $quantity
     *
     * @return TierPriceInterface|null
     */
    public function find(
        TierPriceableInterface $tierPriceableEntity,
        ChannelInterface $channel,
        int $quantity
    ): ?TierPriceInterface {

        Assert::isInstanceOf($tierPriceableEntity, ProductVariant::class);

        $possibleTierPrices = $this->tierPriceRepository->getSortedTierPrice($tierPriceableEntity, $channel);

        $cheapestTierPrice = null;
        /** @var TierPriceInterface[] $tierPricesForChannel */
        foreach ($possibleTierPrices as $tierPrice) {
            if ($tierPrice->getQty() <= $quantity) {
                $cheapestTierPrice = $tierPrice;
                break;
            }
        }

        return $cheapestTierPrice;
    }
}
