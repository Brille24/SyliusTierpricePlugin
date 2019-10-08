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
use function count;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use function get_class;
use ReflectionProperty;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Webmozart\Assert\Assert;

class TierPriceUniqueValidator extends ConstraintValidator
{
    /** @var ManagerRegistry */
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        Assert::isInstanceOf($value, TierPriceInterface::class);
        Assert::isInstanceOf($constraint, TierPriceUniqueConstraint::class);
        /** @var TierPriceInterface $value */
        $fields = $constraint->fields;
        if (0 === count($fields)) {
            throw new ConstraintDefinitionException('At least one field has to be specified.');
        }

        $em = $this->registry->getManagerForClass(get_class($value));
        if ($em === null) {
            throw new ConstraintDefinitionException(
                sprintf(
                    'Unable to find the object manager associated with an entity of class "%s".',
                    get_class($value)
                )
            );
        }
        /* @var $class ClassMetadataInfo */
        $class = $em->getClassMetadata(get_class($value));

        $otherTierPrices = $this->context->getRoot()->getData()->getTierPrices();
        $otherTierPrices = array_filter($otherTierPrices, static function ($tierPrice) use ($value) {
            return  $tierPrice !== $value;
        });

        foreach ($otherTierPrices as $otherTierPrice) {
            if ($this->areDuplicates($fields, $class, $value, $otherTierPrice)) {
                $this->context->buildViolation($constraint->message)->atPath($fields[0])->addViolation();

                return;
            }
        }
    }

    private function areDuplicates(array $fields, ClassMetadata $class, TierPriceInterface $first, TierPriceInterface $second): bool
    {
        Assert::isInstanceOf($class, ClassMetadataInfo::class);

        foreach ($fields as $fieldName) {
            if (!$class->hasField($fieldName) && !$class->hasAssociation($fieldName)) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        'The field "%s" is not mapped by Doctrine, so it cannot be validated for uniqueness.',
                        $fieldName
                    )
                );
            }
            /** @var ReflectionProperty $fieldMetaData */
            $fieldMetaData   = $class->reflFields[$fieldName];
            $fieldValue      = $fieldMetaData->getValue($first);
            $otherFieldValue = $fieldMetaData->getValue($second);
            if ($fieldValue !== $otherFieldValue) {
                return false;
            }
        }

        return true;
    }
}
