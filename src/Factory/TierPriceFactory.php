<?php
/**
 * This file is part of the Brille24 tierprice plugin.
 *
 * (c) Brille24 GmbH
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Factory;

use Brille24\SyliusTierPricePlugin\Entity\ProductVariantInterface;
use Brille24\SyliusTierPricePlugin\Entity\TierPriceInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class TierPriceFactory implements TierPriceFactoryInterface
{
    /** @var FactoryInterface */
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /** {@inheritdoc} */
    public function createNew(): object
    {
        return $this->factory->createNew();
    }

    /** {@inheritdoc} */
    public function createAtProductVariant(
        ProductVariantInterface $productVariant,
        array $options = []
    ): TierPriceInterface {
        /** @var TierPriceInterface $tierPrice */
        $tierPrice = $this->createNew();

        $tierPrice->setQty($options['quantity']);
        $tierPrice->setChannel($options['channel']);
        $tierPrice->setPrice($options['price']);
        $tierPrice->setProductVariant($productVariant);

        $productVariant->addTierPrice($tierPrice);

        return $tierPrice;
    }
}
