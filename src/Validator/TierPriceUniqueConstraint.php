<?php

/**
 * This file is part of the Brille24 tierprice plugin.
 *
 * (c) Mamazu
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Validator;

use Symfony\Component\Validator\Constraint;

class TierPriceUniqueConstraint extends Constraint
{
    /** @var string */
    public $message = 'brille24_tier_price.form.validation.not_unique';

    /** @var string[] */
    public $fields = [];

    public function getRequiredOptions(): array
    {
        return ['fields'];
    }

    public function getTargets(): string
    {
        return Constraint::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return TierPriceUniqueValidator::class;
    }

    public function getDefaultOption(): ?string
    {
        return 'fields';
    }
}
