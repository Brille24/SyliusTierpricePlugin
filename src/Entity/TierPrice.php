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

namespace Brille24\SyliusTierPricePlugin\Entity;

use Brille24\SyliusTierPricePlugin\Repository\TierPriceRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Customer\Model\CustomerGroupInterface;

#[Entity(repositoryClass:TierPriceRepository::class)]
#[Table(name: 'brille24_tierprice')]
#[UniqueConstraint(name: 'no_duplicate_prices', columns: ['qty', 'channel_id', 'product_variant_id', 'customer_group_id'])]
class TierPrice implements TierPriceInterface
{
    /** @var int */
    #[Id, Column(type: 'integer')]
    private $id;

    #[ManyToOne(targetEntity: ChannelInterface::class)]
    private ?ChannelInterface $channel = null;

    #[ManyToOne(targetEntity: ProductVariantInterface::class, inversedBy: 'tierPrices')]
    private ProductVariantInterface $productVariant;

    #[ManyToOne(targetEntity: CustomerGroupInterface::class, inversedBy: 'customerGroup')]
    private ?CustomerGroupInterface $customerGroup = null;

    public function __construct(
        #[Column(type: 'integer')]
        private int $qty = 0,
        #[Column(type: 'integer')]
        private int $price = 0,
    ) {
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
