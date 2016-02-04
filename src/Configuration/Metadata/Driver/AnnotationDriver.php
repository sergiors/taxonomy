<?php

namespace Sergiors\Taxonomy\Configuration\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\DriverInterface;
use Sergiors\Taxonomy\Configuration\Metadata\ClassMetadataInterface;
use Sergiors\Taxonomy\Configuration\Metadata\ClassMetadata;
use Sergiors\Taxonomy\Configuration\Annotation\Embeddable;
use Sergiors\Taxonomy\Configuration\Annotation\Embedded;
use Sergiors\Taxonomy\Configuration\Annotation\Index;
use ReflectionClass;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class AnnotationDriver implements DriverInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(ReflectionClass $reflClass)
    {
        $classMetadata = new ClassMetadata($reflClass->getName());

        foreach ($reflClass->getProperties() as $reflProperty) {
            if (!$annotation = $this->reader->getPropertyAnnotation($reflProperty, Embedded::class)) {
                continue;
            }

            $classMetadata->mapEmbedded($reflProperty->getName(), [
                'class' => $annotation->class,
                'column' => $annotation->column
            ]);

            $this->addNestedEmbedded(
                $reflProperty->getName(),
                new ReflectionClass($annotation->class),
                $classMetadata
            );
        }

        return $classMetadata;
    }

    /**
     * @param string                 $propertyName
     * @param ReflectionClass        $reflClass
     * @param ClassMetadataInterface $classMetadata
     */
    private function addNestedEmbedded(
        $propertyName,
        ReflectionClass $reflClass,
        ClassMetadataInterface $classMetadata
    ) {
        if (!$this->reader->getClassAnnotation($reflClass, Embeddable::class)) {
            return;
        }

        foreach ($reflClass->getProperties() as $reflProperty) {
            if (!$annotation = $this->reader->getPropertyAnnotation($reflProperty, Index::class)) {
                continue;
            }

            $mapping = [
                'propertyName' => $reflProperty->getName(),
                'name' => $annotation->name,
                'type' => $annotation->type
            ];

            $classMetadata->addNestedEmbedded($propertyName, $mapping);
        }
    }
}
