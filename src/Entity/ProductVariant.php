<?php
/**
 * This file is part of the Brille24 tierprice plugin.
 *
 * (c) Brille24 GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brille24\SyliusTierPricePlugin\Entity;

use Brille24\SyliusTierPricePlugin\Traits\{
    TierPriceableInterface, TierPriceableTrait
};
use Sylius\Component\Core\Model\ProductVariant as BaseProductVariant;

/**
 * Class ProductVariant
 *
 * Entity for the product variant with the tier prices implemented as trait.
 *
 * @package Brille24\SyliusTierPricePlugin\Entity
 * @see     TierPriceableTrait
 */
class ProductVariant extends BaseProductVariant
    implements TierPriceableInterface
{
    use TierPriceableTrait;

    public function __construct()
    {
        parent::__construct();
        $this->initTierPriceableTrait();
    }
}
