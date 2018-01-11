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

namespace Brille24\TierPriceBundle\Services;

use Brille24\TierPriceBundle\Entity\TierPriceInterface;
use Brille24\TierPriceBundle\Traits\TierPriceableInterface;
use Sylius\Component\Core\Model\ChannelInterface;

interface TierPriceFinderInterface
{
    public function find(
        TierPriceableInterface $tierPriceableEntity,
        ChannelInterface $channel,
        int $quantity
    ): ?TierPriceInterface;
}
