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

require 'vendor/autoload.php';

use Development\Fixer\Fixer;

$header = <<<'EOF'
This file is part of PHP Development Tools library.

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.

@copyright <date> Michal Sotolar <michal@sotolar.com>

@link https://github.com/misotolar/php-dev
@link https://michal.sotolar.com
EOF;

return Fixer::create(__DIR__)->withHeader($header, ['date' => 2026]);
