<?php
namespace Sergiors\Taxonomy\Configuration\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class Taxonomy implements Annotation
{
    /**
     * @var \Doctrine\ORM\Mapping\Column
     */
    public $column;
}
