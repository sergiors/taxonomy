<?php

namespace Sergiors\Taxonomy\Configuration\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class DiscriminatorColumn implements Annotation
{
    /**
     * @var string
     */
    public $name;
}
