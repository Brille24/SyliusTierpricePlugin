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

namespace Tests\Brille24\SyliusTierPricePlugin\Behat\Context;

use Behat\Behat\Context\Context;
use Brille24\SyliusTierPricePlugin\Entity\ProductVariant;
use Brille24\SyliusTierPricePlugin\Entity\TierPrice;
use Sylius\Component\Core\Model\ProductInterface;

final class TierPriceContext implements Context
{
    /**
     * @Given /^"([^"]*)" has a tier price at (\d+) with ("[^"]+")$/
     */
    public function productHasATierPrice(ProductInterface $product, int $quantity, int $price): void
    {
        /** @var ProductVariant $productVariant */
        $productVariant = $product->getVariants()->toArray()[0];

        $tierPrice = new TierPrice($quantity, $price * 100);
        $productVariant->addTierPrice($tierPrice);
    }
}
