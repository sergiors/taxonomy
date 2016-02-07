<?php

namespace Sergiors\Taxonomy\Tests\Unit;

use Sergiors\Taxonomy\Type\Type;

class DateTimeTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \UnexpectedValueException
     */
    public function shouldReturnUnexpectedValueException()
    {
        Type::getType('datetime')->convertToDatabaseValue('2015-10-11');
    }

    /**
     * @test
     */
    public function shouldReturnNullValue()
    {
        $datetime = Type::getType('datetime');

        $this->assertNull($datetime->convertToDatabaseValue(''));
        $this->assertNull($datetime->convertToDatabaseValue(null));

        $this->assertNull($datetime->convertToPHPValue(''));
        $this->assertNull($datetime->convertToPHPValue(null));
    }
}
