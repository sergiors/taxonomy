<?php
use Doctrine\Common\Annotations\AnnotationRegistry;

if (!$loader = require __DIR__.'/../vendor/autoload.php') {
    throw new \RuntimeException('You need to install dependencies using Composer.');
}

AnnotationRegistry::registerLoader([$loader, 'loadClass']);
