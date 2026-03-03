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

use Development\Fixer\AbstractRuleset;

/**
 * PHP 7.3 default ruleset
 *
 * @author Michal Sotolar <michal@sotolar.com>
 */
class PHP73Ruleset extends AbstractRuleset
{
    /**
     * {@inheritdoc}
     */
    protected $_risky = true;

    /**
     * {@inheritdoc}
     */
    public function getRules(): array
    {
        return [
            '@PSR2' => true,
            '@PhpCsFixer' => true,
            'blank_line_before_statement' => false,
            'concat_space' => [
                'spacing' => 'one',
            ],
            'echo_tag_syntax' => [
                'format' => 'short',
            ],
            'increment_style' => [
                'style' => 'post',
            ],
            'native_function_invocation' => [
                'include' => $this->getNativeFunctions(),
            ],
            'native_constant_invocation' => [
                'include' => $this->getNativeConstants(),
            ],
            'new_with_braces' => false,
            'no_alternative_syntax' => [
                'fix_non_monolithic_code' => false,
            ],
            'ordered_imports' => [
                'sort_algorithm' => 'alpha',
                'imports_order' => [
                    'const',
                    'class',
                    'function',
                ],
            ],
            'phpdoc_align' => [
                'align' => 'left',
            ],
            'phpdoc_annotation_without_dot' => false,
            'phpdoc_no_package' => false,
            'phpdoc_to_comment' => false,
            'phpdoc_summary' => false,
            'protected_to_private' => false,
            'return_assignment' => false,
        ];
    }
}
