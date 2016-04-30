<?php

namespace Sergiors\Taxonomy\Configuration\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use Sergiors\Taxonomy\Configuration\Annotation\Embedded;
use Sergiors\Taxonomy\Configuration\Annotation\Index;
use Sergiors\Taxonomy\Configuration\Metadata\ClassMetadata;
use Sergiors\Taxonomy\Configuration\Metadata\EmbeddedMetadata;
use Sergiors\Taxonomy\Configuration\Metadata\IndexMetadata;
use Sergiors\Taxonomy\Configuration\Metadata\EmbeddedMetadataInterface;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class AnnotationDriver implements MappingDriverInterface
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
     * @param string $className
     *
     * @return ClassMetadata
     */
    public function loadMetadataForClass($className)
    {
        $classMetadata = new ClassMetadata($className);
        $reflClass = new \ReflectionClass($className);

        foreach ($reflClass->getProperties() as $reflProperty) {
            if ($annotation = $this->reader->getPropertyAnnotation($reflProperty, Embedded::class)) {
                $embeddedMetadata = $this->addNestedEmbeddedClasses(
                    new EmbeddedMetadata(
                        $reflProperty->class,
                        $reflProperty->name,
                        $annotation->class,
                        $annotation->column
                    )
                );

                $classMetadata->addEmbeddedClass($embeddedMetadata);
            }
        }

        return $classMetadata;
    }

    /**
     * @param EmbeddedMetadataInterface $embeddedMetadata
     *
     * @return EmbeddedMetadataInterface
     */
    private function addNestedEmbeddedClasses(EmbeddedMetadataInterface $embeddedMetadata)
    {
        $reflClass = new \ReflectionClass($embeddedMetadata->getClassAttribute());

        foreach ($reflClass->getProperties() as $reflProperty) {
            if ($annotation = $this->reader->getPropertyAnnotation($reflProperty, Index::class)) {
                $embeddedMetadata->addEmbeddableClass(
                    new IndexMetadata(
                        $reflProperty->class,
                        $reflProperty->name,
                        $annotation->name,
                        $annotation->type
                    )
                );
            }

            if ($annotation = $this->reader->getPropertyAnnotation($reflProperty, Embedded::class)) {
                $nested = $this->addNestedEmbeddedClasses(
                    new EmbeddedMetadata(
                        $reflProperty->class,
                        $reflProperty->name,
                        $annotation->class
                    )
                );

                $embeddedMetadata->addEmbeddableClass($nested);
            }
        }

        return $embeddedMetadata;
    }
}
