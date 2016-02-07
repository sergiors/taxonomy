<?php

namespace Sergiors\Taxonomy\Tests\Unit;

use Sergiors\Taxonomy\Type\Type;

class TypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function shouldReturnInvalidArgumentException()
    {
        Type::getType('tests');
    }
}
