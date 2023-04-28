<?php

declare(strict_types=1);

/**
 * This file is part of Dimtrovich/Validation.
 *
 * (c) 2023 Dimitri Sitchet Tomkeu <devcode.dst@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use BlitzPHP\CodingStandard\Blitz;
use Nexus\CsConfig\Factory;
use Nexus\CsConfig\Fixer\Comment\NoCodeSeparatorCommentFixer;
use Nexus\CsConfig\FixerGenerator;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->files()
    ->in([
        __DIR__ . '/src',
        // __DIR__ . '/tests',
        // __DIR__ . '/utils',
    ])
    // ->exclude(['ThirdParty'])
    ->notName('#Foobar.php$#')
    ->append([
        __FILE__,
        __DIR__ . '/.php-cs-fixer.no-header.php',
        __DIR__ . '/.php-cs-fixer.user-guide.php',
        // __DIR__ . '/rector.php',
        // __DIR__ . '/spark',
        // __DIR__ . '/user_guide_src/renumerate.php',
    ]);

$overrides = [
    'static_lambda' => false,
];

$options = [
    'cacheFile'    => 'build/.php-cs-fixer.cache',
    'finder'       => $finder,
    'customFixers' => FixerGenerator::create('vendor/nexusphp/cs-config/src/Fixer', 'Nexus\\CsConfig\\Fixer'),
    'customRules'  => [
        NoCodeSeparatorCommentFixer::name() => true,
    ],
];

return Factory::create(new Blitz(), $overrides, $options)->forLibrary(
    'Dimtrovich/Validation',
    'Dimitri Sitchet Tomkeu',
    'devcode.dst@gmail.com',
    2023
);
