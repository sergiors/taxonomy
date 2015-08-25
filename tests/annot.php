<?php
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

require_once __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerFile(__DIR__.'/../src/Mapping/Driver/Annotations.php');

$reflClass = new ReflectionClass('Sergiors\Taxonomy\User');
$props = $reflClass->getProperties();

$reader = new AnnotationReader();
$propAnnotations = $reader->getPropertyAnnotations($props[0]);

var_dump($propAnnotations);
