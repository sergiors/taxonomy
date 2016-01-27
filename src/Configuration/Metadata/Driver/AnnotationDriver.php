<?php

namespace Sergiors\Taxonomy\Configuration\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\DriverInterface;
use Sergiors\Taxonomy\Configuration\Metadata\ClassMetadataInterface;
use Sergiors\Taxonomy\Configuration\Metadata\ClassMetadata;
use Sergiors\Taxonomy\Configuration\Annotation\Embeddable;
use Sergiors\Taxonomy\Configuration\Annotation\Embedded;
use Sergiors\Taxonomy\Configuration\Annotation\Index;

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
    public function loadMetadataForClass(\ReflectionClass $reflClass)
    {
        $classMetadata = new ClassMetadata($reflClass->getName());

        foreach ($reflClass->getProperties() as $reflProperty) {
            $mapping = [];
            $mapping['fieldName'] = $reflProperty->getName();

            $this->addEmbedded(
                $mapping,
                $classMetadata,
                $this->reader->getPropertyAnnotation($reflProperty, Embedded::class)
            );
        }

        foreach ($classMetadata->getEmbeddedClasses() as $property => $embeddableClass) {
            $reflClass = new \ReflectionClass($embeddableClass['class']);

            if ($this->reader->getClassAnnotation($reflClass, Embeddable::class)) {
                $this->nestedEmbedded($reflClass, $classMetadata, $property);
            }
        }

        return $classMetadata;
    }

    /**
     * @param array                  $mapping
     * @param ClassMetadataInterface $metadata
     * @param Embedded|null          $annotation
     */
    private function addEmbedded(
        array $mapping,
        ClassMetadataInterface $metadata,
        Embedded $annotation = null
    ) {
        if (null === $annotation) {
            return;
        }

        $mapping['class'] = $annotation->class;
        $mapping['column'] = $annotation->column;

        $metadata->mapEmbedded($mapping);
    }

    /**
     * @param \ReflectionClass       $reflClass
     * @param ClassMetadataInterface $metadata
     * @param string                 $property
     */
    private function nestedEmbedded(
        \ReflectionClass $reflClass,
        ClassMetadataInterface $metadata,
        $property
    ) {
        foreach ($reflClass->getProperties() as $reflProperty) {
            $mapping = [];
            $mapping['fieldName'] = $reflProperty->getName();

            if ($annotation = $this->reader->getPropertyAnnotation($reflProperty, Index::class)) {
                $mapping['name'] = $annotation->name;
                $metadata->addNestedEmbedded($property, $mapping);
            }
        }
    }
}
