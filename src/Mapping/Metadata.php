<?php
namespace Sergiors\Metadata\Mapping;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class Metadata implements Annotation
{
    /**
     * @var string
     */
    public $defaultClass;

    /**
     * @var string
     */
    public $indexName;
}
