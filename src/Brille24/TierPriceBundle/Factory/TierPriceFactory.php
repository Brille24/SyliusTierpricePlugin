<?php
declare(strict_types=1);

namespace Brille24\TierPriceBundle\Factory;


use Brille24\TierPriceBundle\Entity\ProductVariant;
use Brille24\TierPriceBundle\Entity\TierPrice;
use Brille24\TierPriceBundle\Entity\TierPriceInterface;
use Doctrine\ORM\EntityNotFoundException;
use Sylius\Bundle\CoreBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface;
use Sylius\Component\Product\Factory\ProductVariantFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TierPriceFactory implements ExampleFactoryInterface
{
    /**
     * @var ProductVariantFactory
     */
    private $productVariantFactory;

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

    public function create(array $options = []): TierPriceInterface
    {
        $productVariant = $this->productVariantRepository->findOneByCode($options['product_variant']);
        if ($productVariant === null) {
            throw new EntityNotFoundException('Create the product variant first');
        }

        return $this->createAtProductVariant($options, $productVariant);
    }

    protected function configureOption(OptionsResolver $resolver)
    {
        $resolver->setDefault('quantity', 1);
        $resolver->setAllowedTypes('quantity', 'integer');

        $resolver->setDefault('price', 0);
        $resolver->setAllowedTypes('price', 'integer');

        $resolver->setDefault('product_variant', LazyOption::randomOne($this->productVariantRepository));
        $resolver->setAllowedTypes('quantity', ProductVariant::class);

        $resolver->setDefault('channel', LazyOption::randomOne($this->channelRepository));
        $resolver->setAllowedTypes('quantity', Channel::class);
    }

    public function createAtProductVariant(array $options = [], ProductVariant $productVariant): TierPriceInterface
    {
        $tierPrice = new TierPrice();

        $tierPrice->setQty($options['quantity']);
        $tierPrice->setProductVariant($productVariant);

        $tierPrice->setChannel($this->channelRepository->findOneByCode($options['channel']));
        $tierPrice->setPrice($options['price']);

        $productVariant->addTierPrice($tierPrice);

        return $tierPrice;
    }

}