<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 08/01/18
 * Time: 10:45
 */
declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Tests\Services;

use Brille24\SyliusTierPricePlugin\Services\OrderPricesRecalculator;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItem;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Webmozart\Assert\Assert;

class OrderPricesRecalculatorTest extends \PHPUnit\Framework\TestCase
{
    /** @var OrderProcessorInterface */
    private $orderPriceRecalculator;

    /** @var array */
    private $calculated = [];

    public function setUp(): void
    {
        $productVariantCalculator = $this->createMock(ProductVariantPriceCalculatorInterface::class);
        $calculated               = &$this->calculated;

        $productVariantCalculator->method('calculate')->willReturnCallback(
            function (ProductVariantInterface $productVariant, array $options) use (&$calculated) {
                Assert::keyExists($options, 'quantity');
                Assert::keyExists($options, 'channel');
                $calculated[] = $options['quantity'] * 2;

                return 0;
            });

        $this->orderPriceRecalculator = new OrderPricesRecalculator($productVariantCalculator);
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
            $this->assertEquals($expectedUnitPrice, $this->calculated[$index]);
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
        $orderItem      = $this->createMock(OrderItem::class);
        $productVariant = $this->createMock(ProductVariantInterface::class);

        $orderItem->method('getVariant')->willReturn($productVariant);
        $orderItem->method('getQuantity')->willReturn($quantity);

        return $orderItem;
    }
}
