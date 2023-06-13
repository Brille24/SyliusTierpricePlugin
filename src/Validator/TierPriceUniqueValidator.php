<?php

/**
 * This file is part of the Brille24 tierprice plugin.
 *
 * (c) Brille24 GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Validator;

use Brille24\SyliusTierPricePlugin\Entity\ProductVariantInterface;
use Brille24\SyliusTierPricePlugin\Entity\TierPriceInterface;
use function count;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Persistence\ObjectManager;
use function get_class;
use ReflectionProperty;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Webmozart\Assert\Assert;

class TierPriceUniqueValidator extends ConstraintValidator
{
    public function __construct(private ManagerRegistry $registry)
    {
    }

    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        Assert::isInstanceOf($value, TierPriceInterface::class);
        Assert::isInstanceOf($constraint, TierPriceUniqueConstraint::class);

        /** @var TierPriceUniqueConstraint $constraint */
        $fields = $constraint->fields;
        if (0 === count($fields)) {
            throw new ConstraintDefinitionException('At least one field has to be specified.');
        }

        $em = $this->registry->getManagerForClass($value::class);
        if ($em === null) {
            throw new ConstraintDefinitionException(
                sprintf(
                    'Unable to find the object manager associated with an entity of class "%s".',
                    $value::class,
                ),
            );
        }

        /** @psalm-suppress MixedMethodCall */
        $formData = $this->context->getRoot()->getData();
        if ($formData instanceof ProductInterface && $formData->getVariants()->count() === 1) {
            $formData = $formData->getVariants()->first();
        }
        if (!$formData instanceof ProductVariantInterface) {
            throw new ConstraintDefinitionException('Unable to find ProductVariant in form.');
        }
        $otherTierPrices = $formData->getTierPrices();

        $otherTierPrices = array_filter($otherTierPrices, static fn($tierPrice): bool => $tierPrice !== $value);

        foreach ($otherTierPrices as $otherTierPrice) {
            if ($this->areDuplicates($fields, $em, $value, $otherTierPrice)) {
                $this->context->buildViolation($constraint->message)->atPath($fields[0])->addViolation();

                return;
            }
        }
    }

    /**
     * @param string[] $fields
     */
    private function areDuplicates(array $fields, ObjectManager $em, TierPriceInterface $first, TierPriceInterface $second): bool
    {
        /** @var ClassMetadataInfo $class */
        $class = $em->getClassMetadata($first::class);
        Assert::isInstanceOf($class, ClassMetadataInfo::class);

        foreach ($fields as $fieldName) {
            if (!$class->hasField($fieldName) && !$class->hasAssociation($fieldName)) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        'The field "%s" is not mapped by Doctrine, so it cannot be validated for uniqueness.',
                        $fieldName,
                    ),
                );
            }
            /** @psalm-suppress MixedAssignment $fieldValue */
            $fieldValue = $this->getFieldValue($em, $class, $fieldName, $first);
            /** @psalm-suppress MixedAssignment $fieldValue */
            $otherFieldValue = $this->getFieldValue($em, $class, $fieldName, $second);
            if ($fieldValue !== $otherFieldValue) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return mixed
     */
    private function getFieldValue(ObjectManager $em, ClassMetadataInfo $class, string $fieldName, TierPriceInterface $value)
    {
        /** @var ReflectionProperty $fieldMetaData */
        $fieldMetaData = $class->reflFields[$fieldName];

        /** @psalm-suppress MixedAssignment $fieldValue */
        $fieldValue = $fieldMetaData->getValue($value);

        if (null !== $fieldValue && $class->hasAssociation($fieldName)) {
            /* Ensure the Proxy is initialized before using reflection to
             * read its identifiers. This is necessary because the wrapped
             * getter methods in the Proxy are being bypassed.
             */
            $em->initializeObject($fieldValue);
        }

        return $fieldValue;
    }
}
