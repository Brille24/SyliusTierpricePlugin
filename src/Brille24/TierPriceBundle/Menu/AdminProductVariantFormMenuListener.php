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

namespace Brille24\TierPriceBundle\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\Translation\Translator;

/**
 * Class AdminProductVariantFormMenuListener
 *
 * Adding the tier price point to the menu
 *
 * @package Brille24\TierPriceBundle\Menu
 */
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

        $TIERPRICE_TAB = '@Brille24TierPriceBundle/Resources/views/Admin/ProductVariant/Tab/_tierprice.html.twig';
        $menu->addChild('tierprice', ['position' => 1])
            ->setAttribute('template', $TIERPRICE_TAB)
            ->setLabel($this->translator->trans('sylius.ui.tierprice'))
            ->setLabelAttribute('icon', 'dollar');
    }
}
