<?php

namespace Sergiors\Taxonomy\Configuration\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class Embedded implements Annotation
{
    /**
     * @var string
     */
    public $class;

    /**
     * @var \Doctrine\ORM\Mapping\Column
     */
    public $column;
}
