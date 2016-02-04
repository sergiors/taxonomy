<?php

namespace Sergiors\Taxonomy\Configuration\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class Index implements Annotation
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type = 'text';
}
