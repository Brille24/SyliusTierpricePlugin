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

use Brille24\SyliusTierPricePlugin\Traits\TierPriceableInterface;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

final class ProductVariantPriceCalculator implements ProductVariantPriceCalculatorInterface
{
    /**
     * @var ProductVariantPriceCalculatorInterface
     */
    private $basePriceCalculator;

    /** @var TierPriceFinderInterface */
    private $tierPriceFinder;

    public function __construct(
        ProductVariantPriceCalculatorInterface $basePriceCalculator,
        TierPriceFinderInterface $tierPriceFinder
    ) {
        $this->basePriceCalculator = $basePriceCalculator;
        $this->tierPriceFinder     = $tierPriceFinder;
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
     * </ul>
     *
     * @return int
     */
    public function calculate(ProductVariantInterface $productVariant, array $context): int
    {
        // Return the base price if the quantity is not provided
        if (!array_key_exists('quantity', $context)) {
            return $this->basePriceCalculator->calculate($productVariant, $context);
        }

        // Find a tier price and return it
        if ($productVariant instanceof TierPriceableInterface) {
            $tierPrice = $this->tierPriceFinder->find($productVariant, $context['channel'], $context['quantity']);
            if ($tierPrice !== null) {
                return $tierPrice->getPrice();
            }
        }

        // Return the base price if there are no tier prices
        return $this->basePriceCalculator->calculate($productVariant, $context);
    }
}
