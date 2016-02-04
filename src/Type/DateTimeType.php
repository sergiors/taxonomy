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
        if (null === $value) {
            return $value;
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
        if (null === $value) {
            return $value;
        }

        return new \DateTime::createFromFormat(self::DATETIME_FORMAT, $value);
    }
}
