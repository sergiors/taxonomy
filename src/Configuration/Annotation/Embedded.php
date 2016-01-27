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
     * @var \Sergiors\Taxonomy\Configuration\Annotation\Column
     */
    public $column;
}
