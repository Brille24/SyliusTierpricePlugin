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

namespace Brille24\SyliusTierPricePlugin\Factory;

use Brille24\SyliusTierPricePlugin\Entity\ProductVariantInterface;
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
    private OptionsResolver $optionsResolver;

    public function __construct(
        private ProductVariantRepositoryInterface $productVariantRepository,
        private ChannelRepositoryInterface $channelRepository,
        private TierPriceFactoryInterface $tierPriceFactory,
    ) {
        $this->optionsResolver = new OptionsResolver();
        $this->configureOptions($this->optionsResolver);
    }

    /**
     * Creates a tier price
     *
     * @param array $options The configuration of the tier price
     *
     * @throws EntityNotFoundException
     */
    public function create(array $options = []): TierPriceInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var ProductVariantInterface $productVariant */
        $productVariant = $options['product_variant'];

        return $this->tierPriceFactory->createAtProductVariant($productVariant, $options);
    }

    /**
     * Configuring the options that are allowed in the factory
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('quantity', 1);
        $resolver->setAllowedTypes('quantity', 'integer');

        $resolver->setDefault('price', 0);
        $resolver->setAllowedTypes('price', 'integer');

        $resolver->setDefault('product_variant', LazyOption::randomOne($this->productVariantRepository));
        $resolver->setAllowedTypes('product_variant', [ProductVariantInterface::class, 'string']);
        $resolver->setNormalizer('product_variant', LazyOption::findOneBy($this->productVariantRepository, 'code'));

        $resolver->setDefault('channel', LazyOption::randomOne($this->channelRepository));
        $resolver->setAllowedTypes('channel', [ChannelInterface::class, 'string']);
        $resolver->setNormalizer('channel', LazyOption::findOneBy($this->channelRepository, 'code'));
    }
}
