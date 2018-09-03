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

namespace Brille24\SyliusTierPricePlugin\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Class TierPriceType
 *
 * Form type for the tier price entity
 */
class TierPriceType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('qty', NumberType::class, [
                'label' => 'sylius.ui.amount',
                'required' => true,
                'constraints' => [
                    new Range([
                        'min' => 0,
                        'groups' => 'sylius',
                        'minMessage' => 'Quantity has to be positive',
                    ]),
                    new NotBlank(['groups' => 'sylius']),
                ],
            ])
            ->add('price', MoneyType::class, [
                'label' => 'sylius.ui.price',
                'required' => true,
                'currency' => $options['currency'],
            ])
            ->add('channel', ChannelChoiceType::class, [
                'constraints' => [
                    new NotBlank(['groups' => 'sylius']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired([
                'currency',
            ])
            ->setDefaults([
                'currency' => 'USD',
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'brille24_tier_price';
    }
}
