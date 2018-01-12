<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 09/01/18
 * Time: 11:32
 */


namespace Brille24\Behat\Context;


use Behat\Behat\Context\Context;
use Brille24\TierPriceBundle\Entity\ProductVariant;
use Brille24\TierPriceBundle\Entity\TierPrice;
use Sylius\Component\Core\Model\ProductInterface;

final class TierPriceContext implements Context
{

    /**
     * @Given /^"([^"]*)" has a tier price at (\d+) with ("[^"]+")$/
     */
    public function productHasATierPrice(ProductInterface $product, int $quantity, int $price): void
    {
        /** @var ProductVariant $productVariant */
        $productVariant = array_values($product->getVariants())[0];

        $tierPrice = $this->createTierprice($quantity, $price);
        $productVariant->addTierPrice($tierPrice);
    }

    //<editor-fold desc="Helper Function">
    private function createTierPrice(int $quantity, $price): TierPrice
    {
        $tierPrice = new TierPrice($quantity);
        $tierPrice->setPrice((int)(floatval($price) * 100));

        return $tierPrice;
    }
    //</editor-fold>
}
