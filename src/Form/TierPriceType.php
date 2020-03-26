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

namespace Brille24\SyliusTierPricePlugin\Form;

use Brille24\SyliusTierPricePlugin\Entity\TierPrice;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\CustomerBundle\Form\Type\CustomerGroupChoiceType;
use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TierPriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('qty', NumberType::class, [
            'label'       => 'sylius.ui.amount',
            'required'    => true,
        ]);

        $builder->add('price', MoneyType::class, [
            'label'    => 'sylius.ui.price',
            'required' => true,
            'currency' => $options['currency'],
        ]);

        $builder->add('customerGroup', CustomerGroupChoiceType::class, [
            'required' => false,
        ]);

        $builder->add('channel', ChannelChoiceType::class, [
            'attr' => ['style' => 'display:none'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['currency']);
        $resolver->setDefaults([
           'data_class' => TierPrice::class,
           'currency'   => 'USD',
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'brille24_tier_price';
    }
}
