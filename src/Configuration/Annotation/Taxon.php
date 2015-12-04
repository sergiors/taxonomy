<?php
namespace Sergiors\Taxonomy\Configuration\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class Taxon implements Annotation
{
    /**
     * @var string
     */
    public $name;
}
