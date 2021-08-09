<?php

use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use SlevomatCodingStandard\Sniffs\Commenting\InlineDocCommentDeclarationSniff;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import('etc/coding-standard.php');

    $containerConfigurator->parameters()->set(Option::SKIP, [
        VisibilityRequiredFixer::class => ['*Spec.php'],
    ]);
};
