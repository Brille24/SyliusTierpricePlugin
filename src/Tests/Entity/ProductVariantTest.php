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

namespace Brille24\SyliusTierPricePlugin\Tests\Entity;

use Brille24\SyliusTierPricePlugin\Entity\ProductVariant;
use Brille24\SyliusTierPricePlugin\Entity\TierPrice;
use Brille24\SyliusTierPricePlugin\Entity\TierPriceInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\ChannelInterface;

class ProductVariantTest extends TestCase
{
    private function createTierPrice(ChannelInterface $channel, int $quantity): TierPriceInterface
    {
        $result = new TierPrice($quantity);
        $result->setChannel($channel);
        $result->setQty($quantity);

        return $result;
    }

    /** @dataProvider data_getTierPricesForChannel
     *
     */
    public function test_getTierPricesForChannel(
        array $givenTierPrices,
        array $expectedTierPrices,
        array $channels,
    ): void {
        //## PREPARE
        $productVariant = new ProductVariant();
        $productVariant->setTierPrices($givenTierPrices);

        //## EXECUTE
        $resultEntries = $productVariant->getTierPricesForChannel($channels['testChannel']);

        //## CHECK
        self::assertCount(count($resultEntries), $expectedTierPrices);
        $i = 0;
        foreach ($resultEntries as $entry) {
            /** @var TierPrice $entry */
            self::assertEquals($expectedTierPrices[$i]->getQty(), $entry->getQty());
            ++$i;
        }
    }

    public function data_getTierPricesForChannel(): array
    {
        // We can't put this in setUp() as data providers are called before setUp().
        $testChannel = $this->createMock(ChannelInterface::class);
        $testChannel->method('getId')->willReturn(1);

        $otherChannel = $this->createMock(ChannelInterface::class);
        $otherChannel->method('getId')->willReturn(2);

        $channels = [
            'testChannel' => $testChannel,
            'otherChannel' => $otherChannel,
        ];

        return
            [
                'no tier prices' => [
                    [],
                    [],
                    $channels,
                ],
                'one tier price matches' => [
                    // Input
                    [$this->createTierPrice($testChannel, 1)],
                    // Expected Output
                    [$this->createTierPrice($testChannel, 1)],
                    $channels,
                ],
                'one tier price no match' => [
                    // Input
                    [$this->createTierPrice($otherChannel, 10)],
                    // Expected Output
                    [],
                    $channels,
                ],
                'multiple tier prices' => [
                    // Input
                    [
                        $this->createTierPrice($otherChannel, 1),
                        $this->createTierPrice($otherChannel, 2),
                        $this->createTierPrice($testChannel, 3),
                        $this->createTierPrice($otherChannel, 4),
                        $this->createTierPrice($testChannel, 5),
                    ],
                    // Expected Output
                    [
                        $this->createTierPrice($testChannel, 3),
                        $this->createTierPrice($testChannel, 5),
                    ],
                    $channels,
                ],
            ];
    }
}
