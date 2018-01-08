<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 29/12/17
 * Time: 16:49
 */

declare(strict_types=1);

namespace Brille24\TierPriceBundle\Form\Extension;

use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Brille24\TierPriceBundle\Form\TierPriceType;

class ProductVariantTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('tierPrices', CollectionType::class, [
            'entry_type'    => TierPriceType::class,
            'entry_options' => ['label' => false],
            'allow_add'     => true,
            'allow_delete'  => true,
        ]);
    }

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType(): string
    {
        return ProductVariantType::class;
    }
}
