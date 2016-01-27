<?php

namespace Sergiors\Taxonomy\Configuration\Annotation;

/**
 * @Annotation
 * @Target("ANNOTATION")
 */
final class Column implements Annotation
{
    /**
     * @var string
     */
    public $name;
}
