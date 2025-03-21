<?php

/**
 * This file is part of the Brille24 tierprice plugin.
 *
 * (c) Mamazu
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Tests\Services;

use Brille24\SyliusTierPricePlugin\Services\OrderPricesRecalculator;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItem;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Webmozart\Assert\Assert;

class OrderPricesRecalculatorTest extends TestCase
{
    /** @var OrderProcessorInterface */
    private $orderPriceRecalculator;

    /** @var array */
    private $calculated = [];

    public function setUp(): void
    {
        $productVariantCalculator = $this->createMock(ProductVariantPricesCalculatorInterface::class);
        $calculated = &$this->calculated;

        $productVariantCalculator->method('calculate')->willReturnCallback(
            function (ProductVariantInterface $productVariant, array $options) use (&$calculated): int {
                Assert::keyExists($options, 'quantity');
                Assert::keyExists($options, 'channel');
                $calculated[] = $options['quantity'] * 2;

                return 0;
            },
        );

        $orderProcessor = $this->createMock(OrderProcessorInterface::class);

        $this->orderPriceRecalculator = new OrderPricesRecalculator($productVariantCalculator, $orderProcessor);
    }

    /** @dataProvider dataProcessOrder */
    public function testProcessOrder(array $orderItems, array $expectedUnitPrices): void
    {
        //## PREPARE
        $channel = $this->createMock(ChannelInterface::class);

        $order = $this->createMock(OrderInterface::class);
        $order->method('getChannel')->willReturn($channel);
        $order->method('getItems')->willReturn(new ArrayCollection($orderItems));

        //## EXECUTE
        $this->orderPriceRecalculator->process($order);

        //## CHECK
        foreach ($expectedUnitPrices as $index => $expectedUnitPrice) {
            self::assertEquals($expectedUnitPrice, $this->calculated[$index]);
        }
    }

    public function dataProcessOrder(): array
    {
        return [
            'one product' => [
                [$this->createOrder(2)],
                [4],
            ],
            'multiple products' => [
                [$this->createOrder(1), $this->createOrder(5)],
                [2, 10],
            ],
        ];
    }

    private function createOrder(int $quantity): OrderItemInterface
    {
        $orderItem = $this->createMock(OrderItem::class);
        $productVariant = $this->createMock(ProductVariantInterface::class);

        $orderItem->method('getVariant')->willReturn($productVariant);
        $orderItem->method('getQuantity')->willReturn($quantity);

        return $orderItem;
    }
}
