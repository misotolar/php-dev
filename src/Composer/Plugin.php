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
use Composer\DependencyResolver\Operation\OperationInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\InstallerEvent;
use Composer\Installer\InstallerEvents;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Composer\Util\Filesystem;
use Composer\Util\ProcessExecutor;
use DG\ComposerCleaner\Cleaner;

/**
 * Composer plugin
 *
 * @author Michal Sotolar <michal@sotolar.com>
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * Activation
     */
    public function activate(Composer $composer, IOInterface $io): void
    {
        if (true !== $this->isGlobal($composer) && true !== empty($_ENV['COMPOSER_LOCAL_PATH'])) {
            $composer->getRepositoryManager()->prependRepository(new Repository([], $composer));
        }
    }

    /**
     * Deactivation
     */
    public function deactivate(Composer $composer, IOInterface $io): void
    {
    }

    /**
     * Uninstall
     */
    public function uninstall(Composer $compose, IOInterface $io): void
    {
    }

    /**
     * Subscribed events
     */
    public static function getSubscribedEvents(): array
    {
        return [
            InstallerEvents::PRE_OPERATIONS_EXEC => 'setLocalPackagePath',
            ScriptEvents::POST_INSTALL_CMD => 'runCleaner',
            ScriptEvents::POST_UPDATE_CMD => 'runCleaner',
        ];
    }

    /**
     * Set local package path
     */
    public function setLocalPackagePath(InstallerEvent $event): void
    {
        $composer = $event->getComposer();
        if (false !== $this->isGlobal($composer) || false !== empty($_ENV['COMPOSER_LOCAL_PATH'])) {
            return;
        }

        // Normalización
        foreach ($event->getTransaction()->getOperations() as $operation) {
            if (null !== $package = $this->getOperationPackage($operation)) {
                if ('path' === $package->getDistType() && $package->getDistUrl() === $package->getName()) {
                    $package->setDistUrl($_ENV['COMPOSER_LOCAL_PATH'] . \DIRECTORY_SEPARATOR . $package->getDistUrl());
                }
            }
        }
    }

    /**
     * Dependency cleaner
     */
    public function runCleaner(Event $event): void
    {
        $composer = $event->getComposer();
        if (false !== $this->isGlobal($composer)) {
            return;
        }

        $extra = $composer->getPackage()->getExtra();
        $ignore = $extra['cleaner-ignore'] ?? [];

        $install = $composer->getInstallationManager();
        foreach ($composer->getRepositoryManager()->getLocalRepository()->getPackages() as $package) {
            if ('path' === $package->getDistType() && false !== \is_link($install->getInstallPath($package))) {
                $ignore[$package->getName()] = true;
            }
        }

        \krsort($ignore);

        $cleaner = new Cleaner($event->getIO(), new Filesystem(new ProcessExecutor($event->getIO())));
        $cleaner->clean($event->getComposer()->getConfig()->get('vendor-dir'), $ignore);
    }

    /**
     * Check composer global environment
     */
    private function isGlobal(Composer $composer): bool
    {
        return 0 === \strpos(
            \realpath($composer->getConfig()->getConfigSource()->getName()),
            \realpath($composer->getConfig()->get('home'))
        );
    }

    /**
     * Get operation package if available
     */
    private function getOperationPackage(OperationInterface $operation): ?PackageInterface
    {
        // Instalación
        if ('install' === $operation->getOperationType()) {
            return $operation->getPackage();
        }

        // Actualización
        if ('update' === $operation->getOperationType()) {
            return $operation->getTargetPackage();
        }

        return null;
    }
}
