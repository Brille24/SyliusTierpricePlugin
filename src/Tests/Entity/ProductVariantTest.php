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

namespace Brille24\SyliusTierPricePlugin\Tests\Entity;

use Brille24\SyliusTierPricePlugin\Entity\ProductVariant;
use Brille24\SyliusTierPricePlugin\Entity\TierPrice;
use Brille24\SyliusTierPricePlugin\Entity\TierPriceInterface;
use Sylius\Component\Core\Model\ChannelInterface;

class ProductVariantTest extends \PHPUnit\Framework\TestCase
{
    /** @var ChannelInterface */
    private $testChannel;

    /** @var ChannelInterface */
    private $otherChannel;

    public function __construct(
        ?string $name = null,
        array $data = [],
        string $dataName = ''
    ) {
        parent::__construct($name, $data, $dataName);

        $this->testChannel = $this->createMock(ChannelInterface::class);
        $this->testChannel->method('getId')->willReturn(1);

        $this->otherChannel = $this->createMock(ChannelInterface::class);
        $this->otherChannel->method('getId')->willReturn(2);
    }

    private function createTierPrice(ChannelInterface $channel, int $quantity): TierPriceInterface
    {
        $result = new TierPrice($quantity);
        $result->setChannel($channel);
        $result->setQty($quantity);

        return $result;
    }

    /** @dataProvider data_getTierPricesForChannel
     *
     * @param array $givenTierPrices
     * @param array $expectedTierPrices
     */
    public function test_getTierPricesForChannel(array $givenTierPrices, array $expectedTierPrices): void
    {
        //## PREPARE
        $productVariant = new ProductVariant();
        $productVariant->setTierPrices($givenTierPrices);

        //## EXECUTE
        $resultEntries = $productVariant->getTierPricesForChannel($this->testChannel);

        //## CHECK
        $this->assertEquals(count($resultEntries), count($expectedTierPrices));
        $i = 0;
        foreach ($resultEntries as $entry) {
            /** @var TierPrice $entry */
            $this->assertEquals($expectedTierPrices[$i]->getQty(), $entry->getQty());
            ++$i;
        }
    }

    public function data_getTierPricesForChannel(): array
    {
        return
            [
                'no tier prices' => [
                    [],
                    [],
                ],
                'one tier price matches' => [
                    // Input
                    [$this->createTierPrice($this->testChannel, 1)],
                    // Expected Output
                    [$this->createTierPrice($this->testChannel, 1)],
                ],
                'one tier price no match' => [
                    // Input
                    [$this->createTierPrice($this->otherChannel, 10)],
                    // Expected Output
                    [],
                ],
                'multiple tier prices' => [
                    // Input
                    [
                        $this->createTierPrice($this->otherChannel, 1),
                        $this->createTierPrice($this->otherChannel, 2),
                        $this->createTierPrice($this->testChannel, 3),
                        $this->createTierPrice($this->otherChannel, 4),
                        $this->createTierPrice($this->testChannel, 5),
                    ],
                    // Expected Output
                    [
                        $this->createTierPrice($this->testChannel, 3),
                        $this->createTierPrice($this->testChannel, 5),
                    ],
                ],
            ];
    }
}
