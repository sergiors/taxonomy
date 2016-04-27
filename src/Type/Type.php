<?php

namespace Sergiors\Taxonomy\Type;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
abstract class Type
{
    const TEXT = 'text';
    const DATETIME = 'datetime';

    /**
     * @var array
     */
    private static $typesMap = [
        self::TEXT => 'Sergiors\Taxonomy\Type\TextType',
        self::DATETIME => 'Sergiors\Taxonomy\Type\DateTimeType',
    ];

    /**
     * @var array
     */
    private static $typeObjects = [];

    final private function __construct()
    {
    }

    /**
     * @param string $name
     *
     * @return Type
     */
    public static function getType($name)
    {
        if (!isset(self::$typesMap[$name])) {
            throw new \InvalidArgumentException();
        }

        if (!isset(self::$typeObjects[$name])) {
            self::$typeObjects[$name] = new self::$typesMap[$name]();
        }

        return self::$typeObjects[$name];
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    public function convertToDatabaseValue($value)
    {
        return $value;
    }

    /**
     * @param string $value
     *
     * @return mixed
     */
    public function convertToPHPValue($value)
    {
        return $value;
    }
}
