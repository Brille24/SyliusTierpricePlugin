<?php

declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Form\Components;

use Sylius\Bundle\AdminBundle\Twig\Component\ProductVariant\FormComponent;
use Sylius\Bundle\UiBundle\Twig\Component\LiveCollectionTrait;

class ProductVariantFormComponent extends FormComponent
{
    use LiveCollectionTrait;
}
