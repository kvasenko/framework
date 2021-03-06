<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Tests\Validator\Rule;

use Bluz\Tests;
use Bluz\Validator\Rule\Alpha;

/**
 * Class AlphaTest
 * @package Bluz\Tests\Validator\Rule
 */
class AlphaTest extends Tests\TestCase
{
    /**
     * @dataProvider providerForPass
     */
    public function testValidAlphanumericCharsShouldReturnTrue($validAlpha, $additional)
    {
        $validator = new Alpha($additional);
        $this->assertTrue($validator->validate($validAlpha));
        $this->assertTrue($validator->assert($validAlpha));
    }

    /**
     * @dataProvider providerForFail
     * @expectedException \Bluz\Validator\Exception\ValidatorException
     */
    public function testInvalidAlphanumericCharsShouldReturnFalse($invalidAlpha, $additional)
    {
        $validator = new Alpha($additional);
        $this->assertFalse($validator->validate($invalidAlpha));
        $this->assertFalse($validator->assert($invalidAlpha));
    }

    /**
     * @dataProvider providerForComponentException
     * @expectedException \Bluz\Validator\Exception\ComponentException
     */
    public function testInvalidConstructorParamsShouldThrowComponentException($additional)
    {
        new Alpha($additional);
    }

    /**
     * @dataProvider providerAdditionalChars
     */
    public function testAdditionalCharsShouldBeRespected($additional, $query)
    {
        $validator = new Alpha($additional);
        $this->assertTrue($validator->validate($query));
    }

    /**
     * Check templates
     */
    public function testTemplates()
    {
        $validator = new Alpha();
        $this->assertNotEmpty($validator->__toString());

        $validator = new Alpha('[]');
        $this->assertNotEmpty($validator->__toString());
    }

    /**
     * @return array
     */
    public function providerForPass()
    {
        return array(
            ['', ''],
            ['foobar', ''],
            ['foobar', 'foobar'],
            ['0alg-anet0', '0-9'],
            ['a', ''],
            ["\t", ''],
            ["\n", ''],
            ['foobar', ''],
            ['python_', '_'],
            ['google.com.ua', '.'],
            ['foobar foobar', ''],
            ["\nabc", ''],
            ["\tdef", ''],
            ["\nabc \t", ''],
        );
    }

    /**
     * @return array
     */
    public function providerForFail()
    {
        return array(
            ['@#$', ''],
            ['_', ''],
            ['dgç', ''],
            ['122al', ''],
            ['122', ''],
            [11123, ''],
            [1e21, ''],
            [0, ''],
            [null, ''],
            [new \stdClass, ''],
            [[], ''],
        );
    }

    /**
     * @return array
     */
    public function providerForComponentException()
    {
        return array(
            [new \stdClass],
            [[]],
            [0x2]
        );
    }

    /**
     * @return array
     */
    public function providerAdditionalChars()
    {
        return array(
            ['!@#$%^&*(){}', '!@#$%^&*(){} abc'],
            ['[]?+=/\\-_|"\',<>.', "[]?+=/\\-_|\"',<>. \t \n abc"],
        );
    }
}
