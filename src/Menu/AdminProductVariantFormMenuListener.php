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

namespace Brille24\SyliusTierPricePlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\Translation\Translator;

final class AdminProductVariantFormMenuListener
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function addItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $TIERPRICE_TAB = '@Brille24SyliusTierPricePlugin/Resources/views/Admin/ProductVariant/Tab/_tierprice.html.twig';
        $menu->addChild('tierprice', ['position' => 1])
            ->setAttribute('template', $TIERPRICE_TAB)
            ->setLabel($this->translator->trans('brille24_tier_price.ui.tier_prices'))
            ->setLabelAttribute('icon', 'dollar');
    }
}
