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

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

/**
 * CS Fixer config
 *
 * @author Michal Sotolar <michal@sotolar.com>
 */
class Fixer extends Config
{
    /**
     * Create fixer config instance
     */
    public static function create(string $path, ?AbstractRuleset $ruleset = null, ?Finder $finder = null): self
    {
        $config = new static();

        if (null === $finder) {
            $finder = Finder::create()
                ->name('/\.php$/')
                ->name('/\.phtml$/')
                ->ignoreDotFiles(false)
                ->ignoreVCSIgnored(true)
                ->in($path)
            ;
        }

        if (null === $ruleset) {
            $ruleset = \PHP_MAJOR_VERSION . \PHP_MINOR_VERSION;
            $ruleset = \sprintf('\\Development\\Fixer\\Ruleset\\PHP%dRuleset', $ruleset);
            $ruleset = new $ruleset();
        }

        $config->setRules($ruleset->getRules());
        $config->setIndent($ruleset->getIndent());

        if (null !== $excludes = $ruleset->getExcludes()) {
            $finder->exclude($excludes);
        }

        $config->setFinder($finder);
        $config->setRiskyAllowed($ruleset->isRisky());
        $config->setCacheFile(\sprintf('%s/.php-cs-fixer.cache', $path));

        return $config;
    }

    /**
     * PHPDoc header comment
     */
    public function withHeader(string $header, array $attributes = []): self
    {
        $rules = $this->getRules();

        // Copyright date
        if (false !== isset($attributes['date'])) {
            $current = \date('Y');
            foreach ((array) $attributes['date'] as $date) {
                if (false === \strpos($date, '_') && $date != $current) {
                    $date = \sprintf('%d-%d', $date, $current);
                }

                $header = \preg_replace('/<date>/', $date, $header, 1);
            }
        }

        // Header comment default rules
        if (true !== isset($rules['header_comment'])) {
            $rules['header_comment'] = [
                'comment_type' => 'PHPDoc',
                'location' => 'after_open',
                'separate' => 'both',
            ];
        }

        $rules['header_comment']['header'] = $header;

        return $this->setRules($rules);
    }
}
