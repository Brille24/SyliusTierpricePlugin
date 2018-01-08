<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 02/01/18
 * Time: 09:22
 */

declare(strict_types=1);

namespace Brille24\TierPriceBundle\Menu;

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

        $TIERPRICE_TAB = '@Brille24TierPriceBundle/Resources/views/Admin/ProductVariant/Tab/_tierprice.html.twig';
        $menu->addChild('tierprice', ['position' => 1])
            ->setAttribute('template', $TIERPRICE_TAB)
            ->setLabel($this->translator->trans('sylius.ui.tierprice'))
            ->setLabelAttribute('icon', 'dollar');
    }
}
