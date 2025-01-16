<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests/Behat',
        __DIR__ . '/ecs.php',
    ]);

    $ecsConfig->import('vendor/sylius-labs/coding-standard/ecs.php');

    $ecsConfig->skip([
        VisibilityRequiredFixer::class => ['*Spec.php'],
    ]);

    $ecsConfig->ruleWithConfiguration(HeaderCommentFixer::class, [
        'comment_type' => 'PHPDoc',
        'location' => 'after_open',
        'header' => 'This file is part of the Brille24 tierprice plugin.

        (c) Brille24 GmbH

        For the full copyright and license information, please view the LICENSE
        file that was distributed with this source code.
        ',
    ]);
};

