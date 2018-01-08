<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 04/01/18
 * Time: 11:32
 */

declare(strict_types=1);

namespace Brille24\TierPriceBundle\Services;

use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Brille24\TierPriceBundle\Entity\TierPrice;
use Brille24\TierPriceBundle\Traits\TierPriceableInterface;
use Webmozart\Assert\Assert;

class ProductVariantPriceCalculator implements ProductVariantPriceCalculatorInterface
{

    /**
     * @var ProductVariantPriceCalculatorInterface
     */
    private $basePriceCalculator;

    public function __construct(ProductVariantPriceCalculatorInterface $basePriceCalculator)
    {
        $this->basePriceCalculator = $basePriceCalculator;
    }

    public function calculate(ProductVariantInterface $productVariant, array $context): int
    {
        Assert::keyExists($context, 'quantity');
        $defaultPrice = $this->basePriceCalculator->calculate($productVariant, $context);

        if ($productVariant instanceof TierPriceableInterface && $context['quantity'] > 1) {
            $tierPrice = $this->findTierPrice($productVariant, $context);
            if ($tierPrice !== null) {
                return $tierPrice->getPrice();
            }
        }

        return $defaultPrice;
    }

    private function findTierPrice(TierPriceableInterface $productVariant, array $context): ?TierPrice
    {
        $bestMatch = null;
        list('channel' => $channel, 'quantity' => $quantity) = $context;
        foreach ($productVariant->getTierPrices() as $index => $tierPrice) {
            /**
             * @var TierPrice $tierPrice
             * @var TierPrice $bestMatch
             */
            // If it is a possible tier price
            if ($tierPrice->getChannel() === $channel && $quantity >= $tierPrice->getQty()) {
                // Setting the better match
                if ($bestMatch === null) {
                    $bestMatch = $tierPrice;
                    continue;
                }
                $bestMatch = ($tierPrice->getQty() > $bestMatch->getQty()) ? $tierPrice : $bestMatch;
            }
        }
        return $bestMatch;
    }
}
