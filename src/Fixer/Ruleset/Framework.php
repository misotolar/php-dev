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

namespace Development\Fixer\Ruleset;

/**
 * PHP Framework ruleset
 *
 * @author Michal Sotolar <michal@sotolar.com>
 */
class Framework extends PHP73Ruleset
{
    /**
     * {@inheritdoc}
     */
    protected $_excludes = ['assets', 'core', 'vendor'];

    /**
     * {@inheritdoc}
     */
    protected $_nativeFunctions = [
        'di',
        'util',
        '__',
        '_n',
        '_noop',
        'application_log',
        'console_log',
        'debug_log',
        'exception_log',
        'adminer_object',
        'sg_load_file',
        'sg_encode_file',
        'sg_decode_string',
        'sg_encode_string',
        'sg_get_const',
        'wincache_ucache_get',
        'wincache_ucache_set',
        'wincache_ucache_exists',
        'wincache_ucache_delete',
        'wincache_ucache_info',
    ];

    /**
     * {@inheritdoc}
     */
    protected function getNativeFunctions(): array
    {
        $functions = parent::getNativeFunctions();

        // DI
        $functions[] = 'di';

        // Utils
        $functions[] = 'util';

        // Translation
        $functions[] = '__';
        $functions[] = '_n';
        $functions[] = '_noop';

        // Debug
        $functions[] = 'application_log';
        $functions[] = 'console_log';
        $functions[] = 'debug_log';
        $functions[] = 'exception_log';

        // Adminer
        $functions[] = 'adminer_object';

        // SourceGuardian
        $functions[] = 'sg_load_file';
        $functions[] = 'sg_encode_file';
        $functions[] = 'sg_decode_string';
        $functions[] = 'sg_encode_string';
        $functions[] = 'sg_get_const';

        // WinCache
        $functions[] = 'wincache_ucache_get';
        $functions[] = 'wincache_ucache_set';
        $functions[] = 'wincache_ucache_exists';
        $functions[] = 'wincache_ucache_delete';
        $functions[] = 'wincache_ucache_info';

        return $functions;
    }

    /**
     * {@inheritdoc}
     */
    protected function getNativeConstants(): array
    {
        $constants = parent::getNativeConstants();

        // Application
        $constants[] = 'APP_CNAME';
        $constants[] = 'APP_NAMESPACE';

        // Paths
        $constants[] = 'BASE_PATH';
        $constants[] = 'CORE_PATH';
        $constants[] = 'BASE_ASSETS_PATH';
        $constants[] = 'CORE_ASSETS_PATH';
        $constants[] = 'BASE_LIBRARY_PATH';
        $constants[] = 'CORE_LIBRARY_PATH';
        $constants[] = 'BASE_VIEWS_PATH';
        $constants[] = 'CORE_VIEWS_PATH';

        // Separators
        $constants[] = 'SET_SEPARATOR';
        $constants[] = 'STRING_SEPARATOR';

        // Windows paths
        $constants[] = 'WIN32_PROGRAMFILES';
        $constants[] = 'WIN32_PROGRAMFILES_X86';

        return $constants;
    }
}
