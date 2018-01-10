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

use Brille24\TierPriceBundle\Entity\TierPriceInterface;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Brille24\TierPriceBundle\Entity\TierPrice;
use Brille24\TierPriceBundle\Traits\TierPriceableInterface;
use Webmozart\Assert\Assert;

/**
 * Class ProductVariantPriceCalculator
 *
 * Calcultes the unit price of a given product variant and its amount
 *
 * @package Brille24\TierPriceBundle\Services
 */
final class ProductVariantPriceCalculator implements ProductVariantPriceCalculatorInterface
{

    /**
     * @var ProductVariantPriceCalculatorInterface
     */
    private $basePriceCalculator;

    public function __construct(ProductVariantPriceCalculatorInterface $basePriceCalculator)
    {
        $this->basePriceCalculator = $basePriceCalculator;
    }

    /**
     * Calculates the unit price of a product variant based on the context
     *
     * @param ProductVariantInterface $productVariant
     * @param array                   $context
     * The context has to have the following keys:
     * <ul>
     * <li>channel</li>
     * <li>quantity</li>
     *
     * @return int
     */
    public function calculate(ProductVariantInterface $productVariant, array $context): int
    {
        Assert::keyExists($context, 'quantity');

        // Find a tier price and return it
        if ($productVariant instanceof TierPriceableInterface) {
            $tierPrice = $this->findTierPrice($productVariant, $context['channel'], $context['quantity']);
            if ($tierPrice !== null) {
                return $tierPrice->getPrice();
            }
        }

        return $this->basePriceCalculator->calculate($productVariant, $context);
    }

    /**
     * Finds the cheapest tier price for this product variant
     *
     * @param TierPriceableInterface $productVariant
     * @param ChannelInterface       $channel
     * @param int                    $quantity
     *
     * @return TierPrice|null
     */
    private function findTierPrice(
        TierPriceableInterface $productVariant,
        ChannelInterface $channel,
        int $quantity
    ): ?TierPrice {

        $tierPricesForChannel = $productVariant->getTierPricesForChannel($channel);

        // Filters out all tier prices with amounts lower than purchased
        $tierPricesWithQuantityMatching = array_filter($tierPricesForChannel,
            function (TierPriceInterface $tierPrice) use ($quantity) {
                return $tierPrice->getQty() <= $quantity;
            });


        // Gets the cheapest one
        $cheapestTierprice = array_reduce($tierPricesWithQuantityMatching,
            function (?TierPriceInterface $best, ?TierPriceInterface $tierPrice) {
                $previous = $best ?: $tierPrice;
                return $tierPrice->getPrice() < $previous->getPrice() ? $tierPrice : $previous;
            }, null);

        return $cheapestTierprice;
    }
}
