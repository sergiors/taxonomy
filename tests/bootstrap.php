<?php
use Doctrine\Common\Annotations\AnnotationRegistry;

if (!$loader = require_once __DIR__.'/../vendor/autoload.php') {
    throw new \RuntimeException();
}

AnnotationRegistry::registerLoader([$loader, 'loadClass']);
