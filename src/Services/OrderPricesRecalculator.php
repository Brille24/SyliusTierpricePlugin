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

use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Model\OrderInterface as BaseOrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use TypeError;

final class OrderPricesRecalculator implements OrderProcessorInterface
{
    /**
     * @var ProductVariantPricesCalculatorInterface
     */
    private $productVariantPriceCalculator;

    /**
     * @param ProductVariantPricesCalculatorInterface $productVariantPriceCalculator
     */
    public function __construct(ProductVariantPricesCalculatorInterface $productVariantPriceCalculator)
    {
        $this->productVariantPriceCalculator = $productVariantPriceCalculator;
    }

    /**
     * {@inheritdoc}
     */
    public function process(BaseOrderInterface $order): void
    {
        if (!$order instanceof OrderInterface) {
            throw new TypeError('Order has to implement '.OrderInterface::class);
        }

        $channel = $order->getChannel();

        /** @var OrderInterface $order */
        foreach ($order->getItems() as $item) {
            if ($item->isImmutable()) {
                continue;
            }

            $item->setUnitPrice($this->productVariantPriceCalculator->calculateOriginal(
                $item->getVariant(),
                ['channel' => $channel, 'quantity' => $item->getQuantity(), 'customer' => $order->getCustomer()]
            ));
        }
    }
}
