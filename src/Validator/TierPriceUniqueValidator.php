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

namespace Brille24\SyliusTierPricePlugin\Validator;

use Brille24\SyliusTierPricePlugin\Entity\TierPriceInterface;
use Brille24\SyliusTierPricePlugin\Repository\TierPriceRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

class TierPriceUniqueValidator extends ConstraintValidator
{
    /** @var TierPriceRepositoryInterface */
    private $tierPriceRepository;

    public function __construct(TierPriceRepositoryInterface $tierPriceRepository)
    {
        $this->tierPriceRepository = $tierPriceRepository;
    }

    /**
     * @param mixed $value
     * @param TierPriceUniqueConstraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        Assert::isInstanceOf($value, TierPriceInterface::class);
        /** @var TierPriceInterface $value */
        $tierPrice = $this->tierPriceRepository->getTierPriceForQuantity(
            $value->getProductVariant(),
            $value->getChannel(),
            $value->getCustomerGroup(),
            $value->getQty()
        );

        if ($tierPrice !== null) {
            /** @var TierPriceUniqueConstraint $constraint */
            $this->context->addViolation($constraint->message);
        }
    }
}
