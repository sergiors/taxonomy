<?php

namespace Sergiors\Taxonomy\Tests\Configuration\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use Sergiors\Taxonomy\Configuration\Metadata\Driver\AnnotationDriver;
use Sergiors\Taxonomy\Configuration\Metadata\ClassMetadata;
use Sergiors\Taxonomy\Configuration\Metadata\EmbeddedMetadata;
use Sergiors\Taxonomy\Tests\Fixture\User;

class AnnotationDriverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function loadMetadataForClass()
    {
        $reader = new AnnotationReader();
        $driver = new AnnotationDriver($reader);

        $metadata = $driver->loadMetadataForClass(User::class);

        $this->assertInstanceOf(ClassMetadata::class, $metadata);
        $this->assertInstanceOf(EmbeddedMetadata::class, $metadata->getEmbeddedClasses()[0]);
        $this->assertCount(1, $metadata->getEmbeddedClasses());
        $this->assertCount(3, $metadata->getEmbeddedClasses()[0]->getEmbeddableClasses());
        $this->assertEquals('metadata', $metadata->getEmbeddedClasses()[0]->getPropertyName());
    }
}
