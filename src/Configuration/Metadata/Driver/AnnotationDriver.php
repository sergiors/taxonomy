<?php

namespace Sergiors\Taxonomy\Configuration\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\DriverInterface;
use Sergiors\Taxonomy\Configuration\Metadata\ClassMetadata;
use Sergiors\Taxonomy\Configuration\Metadata\EmbeddedMetadata;
use Sergiors\Taxonomy\Configuration\Metadata\IndexMetadata;
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
            /** @var Embedded $embeddedAnnotation */
            if (!$embeddedAnnotation = $this->reader->getPropertyAnnotation($reflProperty, Embedded::class)) {
                continue;
            }

            $this->addEmbeddableMetadata($embeddedMetadata = new EmbeddedMetadata(
                $reflClass->getName(),
                $reflProperty->getName(),
                $embeddedAnnotation->class,
                $embeddedAnnotation->column
            ));

            $classMetadata->addEmbeddedMetadata($embeddedMetadata);
        }

        return $classMetadata;
    }

    /**
     * @param EmbeddedMetadata $embeddedMetadata
     */
    private function addEmbeddableMetadata(EmbeddedMetadata $embeddedMetadata)
    {
        $reflClass = new ReflectionClass($embeddedMetadata->getClass());

        if (!$this->reader->getClassAnnotation($reflClass, Embeddable::class)) {
            return;
        }

        foreach ($reflClass->getProperties() as $reflProperty) {
            /** @var Index $indexAnnotation */
            if ($indexAnnotation = $this->reader->getPropertyAnnotation($reflProperty, Index::class)) {
                $embeddedMetadata->addEmbeddableMetadata(new IndexMetadata(
                    $reflClass->getName(),
                    $reflProperty->getName(),
                    $indexAnnotation
                ));
            }

            /** @var Embedded $embeddedAnnotation */
            if ($embeddedAnnotation = $this->reader->getPropertyAnnotation($reflProperty, Embedded::class)) {
                $this->addEmbeddableMetadata($nested = new EmbeddedMetadata(
                    $reflClass->getName(),
                    $reflProperty->getName(),
                    $embeddedAnnotation->class,
                    $embeddedAnnotation->column
                ));

                $embeddedMetadata->addEmbeddableMetadata($nested);
            }
        }
    }
}
