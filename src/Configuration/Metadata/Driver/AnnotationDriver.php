<?php

namespace Sergiors\Taxonomy\Configuration\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\DriverInterface;
use Sergiors\Taxonomy\Configuration\Metadata\ClassMetadata;
use Sergiors\Taxonomy\Configuration\Metadata\EmbeddableMetadata;
use Sergiors\Taxonomy\Configuration\Metadata\EmbeddedMetadata;
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
            /** @var Embedded $annotation */
            if ($annotation = $this->reader->getPropertyAnnotation($reflProperty, Embedded::class)) {
                $embeddedMetadata = new EmbeddedMetadata(
                    $reflClass->getName(),
                    $reflProperty->getName(),
                    $annotation->class,
                    $annotation->column
                );

                $this->addEmbeddedMetadata($embeddedMetadata);

                $classMetadata->addEmbeddedMetadata($embeddedMetadata);
            }
        }

        return $classMetadata;
    }

    /**
     * @param EmbeddedMetadata $embeddedMetadata
     */
    private function addEmbeddedMetadata(EmbeddedMetadata $embeddedMetadata) {
        $reflClass = new ReflectionClass($embeddedMetadata->getClass());

        if (null === $this->reader->getClassAnnotation($reflClass, Embeddable::class)) {
            return;
        }

        foreach ($reflClass->getProperties() as $reflProperty) {
            /** @var Index $annotation */
            if ($annotation = $this->reader->getPropertyAnnotation($reflProperty, Index::class)) {
                $embeddableMetadata = new EmbeddableMetadata(
                    $reflClass->getName(),
                    $reflProperty->getName(),
                    $annotation
                );

                $embeddedMetadata->addEmbeddableMetadata($embeddableMetadata);
            }
        }
    }
}
