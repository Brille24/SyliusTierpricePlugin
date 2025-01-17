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

namespace Brille24\SyliusTierPricePlugin\Fixtures;

use Sylius\Bundle\CoreBundle\Fixture\AbstractResourceFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class TierPriceFixture extends AbstractResourceFixture
{
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        /**
         * @psalm-suppress PossiblyNullReference
         * @psalm-suppress MixedMethodCall
         * @psalm-suppress PossiblyUndefinedMethod
         */
        $resourceNode
            ->children()
                ->scalarNode('channel')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('product_variant')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->integerNode('quantity')
                    ->isRequired()
                ->end()
                ->integerNode('price')
                    ->isRequired()
                ->end()
            ->end();
    }

    public function getName(): string
    {
        return 'tier_prices';
    }
}
