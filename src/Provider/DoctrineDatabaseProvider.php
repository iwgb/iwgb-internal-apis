<?php

namespace Iwgb\Internal\Provider;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DoctrineDatabaseProvider implements ServiceProviderInterface {

    /**
     * {@inheritdoc}
     */
    public function register(Container $c) {
        $c[Provider::DATABASE] = function (Container $c): EntityManager {
            $settings = $c[Provider::SETTINGS]['db'];

            $config = Setup::createAnnotationMetadataConfiguration(
                [$settings['entityDir']],
                $settings['devMode'],
            );

            $config->setMetadataDriverImpl(
                new AnnotationDriver(
                    new AnnotationReader,
                    $settings['entityDir'],
                ),
            );

            $config->setMetadataCacheImpl(
                new FilesystemCache(APP_ROOT . '/var/doctrine'),
            );

            return EntityManager::create(
                $settings['connection'],
                $config,
            );
        };
    }
}