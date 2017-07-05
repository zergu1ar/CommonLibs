<?php

namespace Zergular\Common\Tests;

use Zergular\Common\AbstractEntity;
use Zergular\Common\UnknownMethodException;

/**
 * Class AbstractEntityTest
 * @package Zergular\Common\Tests
 */
class AbstractEntityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param int $id
     * @param string $created
     * @param string $updated
     * @param array $expectedArray
     *
     * @dataProvider getSetDataProvider
     */
    public function testSettersAndGetters($id, $created, $updated, $expectedArray)
    {
        $entity = new AbstractEntity;
        $entity->setCreated($created)
            ->setUpdated($updated)
            ->setId($id);

        $this->assertEquals($id, $entity->getId());
        $this->assertEquals($created, $entity->getCreated());
        $this->assertEquals($updated, $entity->getUpdated());
        $this->assertEquals($expectedArray, $entity->toArray());
    }

    /**
     * @return array
     */
    public function getSetDataProvider()
    {
        return [
            [
                1,
                '2016-07-05 14:24:01',
                '2016-07-08 14:24:01',
                [
                    'created' => '2016-07-05 14:24:01',
                    'updated' => '2016-07-08 14:24:01'
                ]
            ],
            [
                25,
                '2017-07-03 14:24:01',
                '2017-07-05 14:24:01',
                [
                    'created' => '2017-07-03 14:24:01',
                    'updated' => '2017-07-05 14:24:01'
                ]
            ]
        ];
    }

    /**
     * @param string $name
     * @param mixed $param
     * @param string $expected
     *
     * @dataProvider exceptionDataProvider
     */
    public function testNonExistsSetterOrGetter($name, $param, $expected)
    {
        $this->setExpectedException($expected);
        $entity = new AbstractEntity;
        $entity->{$name}($param);
    }

    /**
     * @return array
     */
    public function exceptionDataProvider()
    {
        return [
            [
                'setCheck',
                5,
                UnknownMethodException::class
            ],
            [
                'getTest',
                'string',
                UnknownMethodException::class
            ]
        ];
    }
}
