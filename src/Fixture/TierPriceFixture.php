<?php

declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Fixture;

use Sylius\Bundle\CoreBundle\Fixture\AbstractResourceFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class TierPriceFixture extends AbstractResourceFixture
{
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
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

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'tier_prices';
    }
}
