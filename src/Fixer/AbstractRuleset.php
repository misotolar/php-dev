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

namespace Development\Fixer;

use PhpCsFixer\RuleSet\AbstractRuleSetDescription;

/**
 * Abstract Fixer ruleset
 *
 * @author Michal Sotolar <michal@sotolar.com>
 */
abstract class AbstractRuleset extends AbstractRuleSetDescription
{
    /**
     * Allow risky rules
     *
     * @var bool
     */
    protected $_risky = false;

    /**
     * Indent
     *
     * @var string
     */
    protected $_indent = '    ';

    /**
     * Excludes
     *
     * @var array
     */
    protected $_excludes = ['vendor'];

    /**
     * Native function invocation
     *
     * @var array
     */
    protected $_nativeFunctions = [];

    /**
     * Native constant invocation
     *
     * @var array
     */
    protected $_nativeConstants = [];

    /**
     * Ruleset description
     */
    public function getDescription(): string
    {
        return $this->getName();
    }

    /**
     * Ruleset rules
     */
    abstract public function getRules(): array;

    /**
     * Allow risky rules
     */
    public function isRisky(): bool
    {
        return $this->_risky;
    }

    /**
     * Code indent
     */
    public function getIndent(): string
    {
        return $this->_indent;
    }

    /**
     * Ruleset finder excludes
     */
    public function getExcludes(): array
    {
        return $this->_excludes;
    }

    /**
     * Native function invocation
     */
    protected function getNativeFunctions(): array
    {
        return \array_merge(['@internal'], $this->_nativeFunctions);
    }

    /**
     * Native constant invocation
     */
    protected function getNativeConstants(): array
    {
        return $this->_nativeConstants;
    }
}
