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
    /**
     * @var ProductVariantRepositoryInterface
     */
    private $productVariantRepository;

    /**
     * @var ChannelRepositoryInterface
     */
    private $channelRepository;

    /**
     * @var TierPriceFactoryInterface
     */
    private $tierPriceFactory;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    public function __construct(
        ProductVariantRepositoryInterface $productVariantRepository,
        ChannelRepositoryInterface $channelRepository,
        TierPriceFactoryInterface $tierPriceFactory
    ) {
        $this->productVariantRepository = $productVariantRepository;
        $this->channelRepository        = $channelRepository;
        $this->tierPriceFactory         = $tierPriceFactory;
        $this->optionsResolver          = new OptionsResolver();
        $this->configureOptions($this->optionsResolver);
    }

    /**
     * Creates a tier price
     *
     * @param array $options The configuration of the tier price
     *
     * @return TierPriceInterface
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
        $resolver->setAllowedTypes('product_variant', [ProductVariantInterface::class, 'string']);
        $resolver->setNormalizer('product_variant', LazyOption::findOneBy($this->productVariantRepository, 'code'));

        $resolver->setDefault('channel', LazyOption::randomOne($this->channelRepository));
        $resolver->setAllowedTypes('channel', [ChannelInterface::class, 'string']);
        $resolver->setNormalizer('channel', LazyOption::findOneBy($this->channelRepository, 'code'));
    }
}
