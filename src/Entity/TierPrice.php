<?php

/**
 * This file is part of the Brille24 tierprice plugin.
 *
 * (c) Brille24 GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Entity;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Customer\Model\CustomerGroupInterface;

class TierPrice implements TierPriceInterface
{
    /** @var int */
    private $id;

    private ?ChannelInterface $channel = null;

    private ProductVariantInterface $productVariant;

    private ?CustomerGroupInterface $customerGroup = null;

    public function __construct(private int $qty = 0, private int $price = 0)
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getQty(): int
    {
        return $this->qty;
    }

    public function setQty(int $qty): void
    {
        $this->qty = max($qty, 0);
    }

    public function getProductVariant(): ProductVariantInterface
    {
        return $this->productVariant;
    }

    public function setProductVariant(ProductVariantInterface $productVariant): void
    {
        $this->productVariant = $productVariant;
    }

    public function getChannel(): ?ChannelInterface
    {
        return $this->channel;
    }

    public function setChannel(?ChannelInterface $channel): void
    {
        $this->channel = $channel;
    }

    public function getCustomerGroup(): ?CustomerGroupInterface
    {
        return $this->customerGroup;
    }

    public function setCustomerGroup(?CustomerGroupInterface $customerGroup): void
    {
        $this->customerGroup = $customerGroup;
    }
}
