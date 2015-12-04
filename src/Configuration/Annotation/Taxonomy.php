<?php
namespace Sergiors\Taxonomy\Configuration\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class Taxonomy implements Annotation
{
    /**
     * @var string
     */
    public $class;

    /**
     * @var string
     */
    public $index;

    /**
     * @var \Doctrine\ORM\Mapping\Column
     */
    public $column;
}
