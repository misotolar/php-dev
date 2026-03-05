<?php

/**
 * This file is part of PHP Development Tools library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright 2026 Michal Sotolar <michal@sotolar.com>
 *
 * @link https://github.com/misotolar/php-dev
 * @link https://michal.sotolar.com
 */

if (true !== isset($argv[1]) || true !== isset($argv[2]) || true !== \file_exists($argv[2])) {
    exit;
}

$fixer = null;
foreach (['.php-cs-fixer.php', '.php-cs-fixer.dist.php'] as $path) {
    $path = \implode(\DIRECTORY_SEPARATOR, [$argv[1], $path]);
    if (false !== \file_exists($path)) {
        $fixer = $path;
    }
}

if (null === $fixer) {
    exit;
}

$fixer = require $fixer;
if (null === $header = $fixer->getHeaderAsComment()) {
    exit;
}

$output = \file_get_contents($argv[2]);
if (0 === \strpos($output, '/**') || 0 === \strpos($output, $fixer->getLineEnding() . '/**')) {
    $current = \substr($output, 0, \strpos($output, ' */') + 3);
    if (false !== \strpos($current, '@copyright')) {
        $output = \substr($output, \strlen($current));
    }
}

$output = $header . \ltrim($output);
\file_put_contents($argv[2], $output);
