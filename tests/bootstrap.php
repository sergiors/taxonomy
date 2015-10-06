<?php
use Doctrine\Common\Annotations\AnnotationRegistry;

/* @var $loader \Composer\Autoload\ClassLoader $loader */
if (!$loader = require __DIR__.'/../vendor/autoload.php') {
    throw new \RuntimeException();
}

// Registering loaders
$loader->register();

AnnotationRegistry::registerLoader([$loader, 'loadClass']);
