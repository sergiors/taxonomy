<?php

namespace Sergiors\Taxonomy\Configuration\Metadata;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
interface MetadataInterface
{
    /**
     * @return string
     */
    public function getClassName();

    /**
     * @return string
     */
    public function getPropertyName();

    /**
     * @param object $entity
     *
     * @return mixed
     */
    public function getValue($entity);

    /**
     * @param object $entity
     * @param mixed  $value
     *
     * @return mixed
     */
    public function setValue($entity, $value);
}
