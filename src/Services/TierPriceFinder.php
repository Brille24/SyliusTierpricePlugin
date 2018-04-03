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

namespace Brille24\SyliusTierPricePlugin\Services;

use Brille24\SyliusTierPricePlugin\Entity\{
    ProductVariant, TierPriceInterface
};
use Brille24\SyliusTierPricePlugin\Repository\TierPriceRepository;
use Brille24\SyliusTierPricePlugin\Traits\TierPriceableInterface;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\ChannelInterface;
use Webmozart\Assert\Assert;

/**
 * Class TierPriceFinder
 *
 * Finds the cheapest tier price with a given channel
 *
 * @package Brille24\SyliusTierPricePlugin\Services
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

        $possibleTierPrices = $this->tierPriceRepository->getSortedTierPrices($tierPriceableEntity, $channel);

        $cheapestTierPrice = null;
        /** @var TierPriceInterface[] $tierPricesForChannel */
        foreach ($possibleTierPrices as $tierPrice) {
            if ($tierPrice->getQty() > $quantity) {
                break;
            }
            $cheapestTierPrice = $tierPrice;
        }

        return $cheapestTierPrice;
    }
}
