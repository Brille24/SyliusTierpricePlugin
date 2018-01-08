<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 29/12/17
 * Time: 16:34
 */

namespace Brille24\TierPriceBundle\Entity;

use Brille24\TierPriceBundle\Traits\TierPriceableInterface;
use Sylius\Component\Core\Model\ProductVariant as BaseProductVariant;
use Brille24\TierPriceBundle\Traits\TierPriceableTrait;

class ProductVariant extends BaseProductVariant
    implements TierPriceableInterface
{
    use TierPriceableTrait;

}
