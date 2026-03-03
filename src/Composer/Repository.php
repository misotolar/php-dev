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

namespace Development\Composer;

use Composer\Composer;
use Composer\Json\JsonFile;
use Composer\Package\Loader\ArrayLoader;
use Composer\Package\Version\VersionParser;
use Composer\Repository\ArrayRepository;

/**
 * Dynamic repository for local development
 *
 * @author Michal Sotolar <michal@sotolar.com>
 */
class Repository extends ArrayRepository
{
    /**
     * Composer
     *
     * @var Composer
     */
    protected $_composer;

    /**
     * Use symlinks
     *
     * @var bool
     */
    protected $_symlink = false;

    /**
     * Constructor
     *
     * @param array $packages Repository packages
     */
    public function __construct(array $packages, Composer $composer)
    {
        parent::__construct($packages);

        $this->_symlink = 0 === \strpos(
            \realpath($composer->getConfig()->getConfigSource()->getName()),
            \realpath($_ENV['COMPOSER_LOCAL_PATH'])
        );

        $this->_composer = $composer;
    }

    /**
     * Load repository packages
     */
    public function loadPackages(array $packageNameMap, array $acceptableStabilities, array $stabilityFlags, array $alreadyLoaded = [])
    {
        $loader = new ArrayLoader(new VersionParser);
        $result = ['namesFound' => [], 'packages' => []];

        foreach (\array_keys($packageNameMap) as $name) {
            $url = \str_replace('/', \DIRECTORY_SEPARATOR, $name);

            if (false === $url = \realpath($_ENV['COMPOSER_LOCAL_PATH'] . \DIRECTORY_SEPARATOR . $url)) {
                continue;
            }

            if (false === $composer = \realpath($url . \DIRECTORY_SEPARATOR . 'composer.json')) {
                continue;
            }

            $package = JsonFile::parseJson(\file_get_contents($composer));
            if (true !== isset($package['version'])) {
                $package['version'] = 'dev-master';
            }

            $package = $loader->load($package);

            $package->setDistUrl($name);
            $package->setDistType('path');
            $package->setDistReference('dev-master');
            $package->setTransportOptions([
                'symlink' => $this->getSymlinkOption(),
            ]);

            if (true !== $this->getSymlinkOption()) {
                $package->setDistReference((new \DateTime)->format('YmdHis'));
            }

            $result['namesFound'][] = $name;
            $result['packages'][] = $package;
        }

        return $result;
    }

    /**
     * Check use of symlinks
     */
    private function getSymlinkOption(): bool
    {
        return 0 === \strpos(
            \realpath($this->_composer->getConfig()->getConfigSource()->getName()),
            \realpath($_ENV['COMPOSER_LOCAL_PATH'])
        );
    }
}
