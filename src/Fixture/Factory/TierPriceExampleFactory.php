<?php

declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Fixture\Factory;

use Brille24\SyliusTierPricePlugin\Entity\ProductVariant;
use Brille24\SyliusTierPricePlugin\Entity\TierPrice;
use Doctrine\ORM\EntityNotFoundException;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Bundle\CoreBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
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
        $this->channelRepository = $channelRepository;
    }

    /**
     * Creates a tierprice
     *
     * @param array $options The configuration of the tierprice
     *
     * @return TierPrice
     *
     * @throws EntityNotFoundException
     */
    public function create(array $options = []): TierPrice
    {
        /** @var ProductVariant|null $productVariant */
        $productVariant = $this->productVariantRepository->findOneBy(['code' => $options['product_variant']]);
        if ($productVariant === null) {
            throw new EntityNotFoundException('Create the product variant first');
        }

        return $this->createAtProductVariant($options, $productVariant);
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
        $resolver->setAllowedTypes('product_variant', ProductVariant::class);

        $resolver->setDefault('channel', LazyOption::randomOne($this->channelRepository));
        $resolver->setAllowedTypes('channel', ChannelInterface::class);
    }

    /**
     * Creates a product variant and attaches the tier price
     *
     * @param array          $options
     * @param ProductVariant $productVariant
     *
     * @return TierPrice
     */
    private function createAtProductVariant(array $options = [], ProductVariant $productVariant): TierPrice
    {
        $tierPrice = new TierPrice();

        $tierPrice->setQty($options['quantity']);
        $tierPrice->setProductVariant($productVariant);

        $tierPrice->setChannel($this->channelRepository->findOneBy(['code' => $options['channel']]));
        $tierPrice->setPrice($options['price']);

        $productVariant->addTierPrice($tierPrice);

        return $tierPrice;
    }
}
