<?php

/**
 * This file is part of the Brille24 tierprice plugin.
 *
 * (c) Brille24 GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

#[AsEventlistener(event: 'sylius.menu.admin.product_variant.form', method: 'addItems')]
final class AdminProductVariantFormMenuListener
{
    public function addItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $TIERPRICE_TAB = '@Brille24SyliusTierPricePlugin/Admin/ProductVariant/Tab/_tierprice.html.twig';
        $menu->addChild('tierprice', ['position' => 1])
            ->setAttribute('template', $TIERPRICE_TAB)
            ->setLabel('brille24_tier_price.ui.tier_prices')
            ->setLabelAttribute('icon', 'dollar');
    }
}
