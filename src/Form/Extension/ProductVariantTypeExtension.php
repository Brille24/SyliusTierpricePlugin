<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 29/12/17
 * Time: 16:49
 */
declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Form\Extension;

use Brille24\SyliusTierPricePlugin\Form\TierPriceType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

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

    /** {@inheritdoc}     */
    public function getExtendedTypes(): array
    {
        return [ProductVariantType::class];
    }

    /** {@inheritdoc} */
    public function getExtendedType(): string
    {
        return $this->getExtendedTypes()[0];
    }
}
