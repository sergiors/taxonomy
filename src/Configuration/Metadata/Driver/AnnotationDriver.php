<?php
namespace Sergiors\Taxonomy\Configuration\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\DriverInterface;
use Sergiors\Taxonomy\Configuration\Metadata\ClassMetadataInterface;
use Sergiors\Taxonomy\Configuration\Metadata\ClassMetadata;
use Sergiors\Taxonomy\Configuration\Annotation\Taxonomy;
use Sergiors\Taxonomy\Configuration\Annotation\Taxon;
use ReflectionClass;
use ReflectionProperty;

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
        $properties = $reflClass->getProperties();

        foreach ($properties as $property) {
            $this->readProperty($property, $classMetadata);
        }

        return $classMetadata;
    }

    /**
     * @param ReflectionProperty $reflProperty
     * @param ClassMetadataInterface $classMetadata
     */
    private function readProperty(ReflectionProperty $reflProperty, ClassMetadataInterface $classMetadata)
    {
        if ($reflProperty->getDeclaringClass()->getName() != $classMetadata->getName()) {
            return;
        }

        $annotations = $this->reader->getPropertyAnnotations($reflProperty);

        foreach ($annotations as $annotation) {
            if ($annotation instanceof Taxonomy) {
                $classMetadata->setTaxonomy($reflProperty->name);
                $classMetadata->setColumn($annotation->column);
            }

            if ($annotation instanceof Taxon) {
                $classMetadata->addTaxon($reflProperty->name, $annotation->class);
            }
        }
    }
}
