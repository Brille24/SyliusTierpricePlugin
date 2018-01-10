<?php

namespace Brille24\TierPriceBundle;

use Brille24\TierPriceBundle\DependencyInjection\Brille24TierPriceExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Brille24TierPriceBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new Brille24TierPriceExtension();
    }
}
