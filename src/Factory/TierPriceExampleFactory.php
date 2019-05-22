<?php

declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Factory;

use Brille24\SyliusTierPricePlugin\Entity\ProductVariantInterface;
use Brille24\SyliusTierPricePlugin\Entity\TierPrice;
use Brille24\SyliusTierPricePlugin\Entity\TierPriceInterface;
use Doctrine\ORM\EntityNotFoundException;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Bundle\CoreBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TierPriceExampleFactory extends AbstractExampleFactory
{
    /**
     * @var ProductVariantRepositoryInterface
     */
    private $productVariantRepository;

    /**
     * @var ChannelRepositoryInterface
     */
    private $channelRepository;

    public function __construct(
        ProductVariantRepositoryInterface $productVariantRepository,
        ChannelRepositoryInterface $channelRepository
    ) {
        $this->productVariantRepository = $productVariantRepository;
        $this->channelRepository        = $channelRepository;
    }

    /**
     * Creates a tierprice
     *
     * @param array $options The configuration of the tierprice
     *
     * @return TierPriceInterface
     *
     * @throws EntityNotFoundException
     */
    public function create(array $options = []): TierPriceInterface
    {
        $tierPrice = new TierPrice();

        $tierPrice->setQty($options['quantity']);
        $tierPrice->setChannel($options['channel']);
        $tierPrice->setPrice($options['price']);

        /** @var ProductVariantInterface $productVariant */
        $productVariant = $options['product_variant'];
        $tierPrice->setProductVariant($productVariant);
        $productVariant->addTierPrice($tierPrice);

        return $tierPrice;
    }

    /**
     * Configuring the options that are allowed in the factory
     *
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('quantity', 1);
        $resolver->setAllowedTypes('quantity', 'integer');

        $resolver->setDefault('price', 0);
        $resolver->setAllowedTypes('price', 'integer');

        $resolver->setDefault('product_variant', LazyOption::randomOne($this->productVariantRepository));
        $resolver->setAllowedTypes('product_variant', ProductVariantInterface::class);
        $resolver->setNormalizer('product_variant', LazyOption::findOneBy($this->productVariantRepository, 'code'));

        $resolver->setDefault('channel', LazyOption::randomOne($this->channelRepository));
        $resolver->setAllowedTypes('channel', ChannelInterface::class);
        $resolver->setNormalizer('channel', LazyOption::findOneBy($this->channelRepository, 'code'));
    }
}
