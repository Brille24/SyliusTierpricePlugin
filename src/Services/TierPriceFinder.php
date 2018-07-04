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

use Brille24\SyliusTierPricePlugin\Entity\ProductVariant;
use Brille24\SyliusTierPricePlugin\Entity\TierPriceInterface;
use Brille24\SyliusTierPricePlugin\Repository\TierPriceRepository;
use Brille24\SyliusTierPricePlugin\Traits\TierPriceableInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use TypeError;

/**
 * Class TierPriceFinder
 *
 * Finds the cheapest tier price with a given channel
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
        if (!$tierPriceableEntity instanceof ProductVariant) {
            throw new TypeError('The tierpriceable entity must be a ' . ProductVariant::class);
        }

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
