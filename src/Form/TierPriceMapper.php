<?php

declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Form;

use Symfony\Component\Form\DataMapperInterface;
use Traversable;

class TierPriceMapper implements DataMapperInterface
{
    public function __construct(private DataMapperInterface $inner) {}

    public function mapDataToForms($viewData, Traversable $forms)
    {
        $this->inner->mapDataToForms($viewData, $forms);
    }

    public function mapFormsToData(Traversable $forms, &$viewData)
    {
        $formData = iterator_to_array($forms);
        if ($formData['qty']->getData() === null
            && $formData['price']->getData() === null
            && $formData['channel']->getData() === null
        ) {
            $viewData = null;
        } else {
            $this->inner->mapFormsToData($forms, $viewData);
        }
    }
}
