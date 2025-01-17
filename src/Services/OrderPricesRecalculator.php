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
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Order\Model\OrderInterface as BaseOrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use TypeError;

final class OrderPricesRecalculator implements OrderProcessorInterface
{
    public function __construct(
        private ProductVariantPricesCalculatorInterface $productVariantPriceCalculator,
        private OrderProcessorInterface $orderProcessor,
    ) {
    }

    public function process(BaseOrderInterface $order): void
    {
        if (!$order instanceof OrderInterface) {
            throw new TypeError('Order has to implement ' . OrderInterface::class);
        }

        $this->orderProcessor->process($order);

        $channel = $order->getChannel();

        foreach ($order->getItems() as $item) {
            if ($item->isImmutable()) {
                continue;
            }

            /** @var ProductVariantInterface $variant */
            $variant = $item->getVariant();

            $item->setUnitPrice($this->productVariantPriceCalculator->calculate(
                $variant,
                ['channel' => $channel, 'quantity' => $item->getQuantity(), 'customer' => $order->getCustomer()],
            ));
        }
    }
}
