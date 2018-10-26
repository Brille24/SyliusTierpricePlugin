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

use Sylius\Bundle\AdminBundle\Event\ProductMenuBuilderEvent;
use Symfony\Component\Translation\Translator;

final class AdminProductFormMenuListener
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function addItems(ProductMenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        if ($event->getProduct()->isConfigurable()) {
            return;
        }

        $TIERPRICE_TAB = '@Brille24SyliusTierPricePlugin/Resources/views/Admin/ProductVariant/Tab/_tierprice.html.twig';
        $menu
            ->addChild('tierprice')
            ->setAttribute('template', $TIERPRICE_TAB)
            ->setLabel($this->translator->trans('brille24_tier_price.ui.tier_prices'))
            ->setLabelAttribute('icon', 'dollar');
    }
}
