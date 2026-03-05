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

\system(\sprintf(
    '"%s" fix --config="%s" "%s"',
    \implode(\DIRECTORY_SEPARATOR, [__DIR__, '..', 'vendor', 'bin', 'php-cs-fixer']),
    $fixer,
    $argv[2]
), $ret);

exit($ret);
