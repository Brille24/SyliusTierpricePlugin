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

namespace Brille24\SyliusTierPricePlugin\Form\Components;

use Sylius\Bundle\AdminBundle\Twig\Component\ProductVariant\FormComponent;
use Sylius\Bundle\UiBundle\Twig\Component\LiveCollectionTrait;

class ProductVariantFormComponent extends FormComponent
{
    use LiveCollectionTrait;
}
