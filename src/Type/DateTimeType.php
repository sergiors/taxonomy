<?php

namespace Sergiors\Taxonomy\Type;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class DateTimeType extends Type
{
    /**
     * @var string
     */
    const DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @param mixed $value
     *
     * @return string
     */
    public function convertToDatabaseValue($value)
    {
        if ('' === $value || null === $value) {
            return null;
        }

        if ($value instanceof \DateTime) {
            return $value->format(self::DATETIME_FORMAT);
        }

        throw new \UnexpectedValueException();
    }

    /**
     * @param string $value
     *
     * @return \DateTime
     */
    public function convertToPHPValue($value)
    {
        if ('' === $value || null === $value) {
            return null;
        }

        return \DateTime::createFromFormat(self::DATETIME_FORMAT, $value);
    }
}
