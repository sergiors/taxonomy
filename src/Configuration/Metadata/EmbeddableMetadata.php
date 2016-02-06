<?php

namespace Sergiors\Taxonomy\Configuration\Metadata;

use Metadata\PropertyMetadata;
use Sergiors\Taxonomy\Configuration\Annotation\Index;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class EmbeddableMetadata extends PropertyMetadata
{
    /**
     * @var Index
     */
    private $index;

    /**
     * @param string $class
     * @param string $name
     * @param Index  $index
     */
    public function __construct($class, $name, Index $index)
    {
        parent::__construct($class, $name);

        $this->index = $index;
    }

    /**
     * @return Index
     */
    public function getIndex()
    {
        return $this->index;
    }
}
