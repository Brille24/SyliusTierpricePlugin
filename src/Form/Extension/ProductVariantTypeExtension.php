<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 29/12/17
 * Time: 16:49
 */
declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Form\Extension;

use Brille24\SyliusTierPricePlugin\Form\Type\TierPriceType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ProductVariantTypeExtension
 *
 * Extending the product variant and adding the tier price entity
 */
class ProductVariantTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('tierPrices', CollectionType::class, [
            'entry_type' => TierPriceType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
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
