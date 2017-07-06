<?php

namespace Zergular\Common\Tests;

use Medoo\Medoo;
use Zergular\Common\AbstractEntity;
use Zergular\Common\AbstractManager;
use Zergular\Common\EntityInterface;

/**
 * Class AbstractManagerTest
 * @package Zergular\Common\Tests
 */
class AbstractManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $stub;

    public function setUp()
    {
        $this->stub = $this->getMockBuilder(Medoo::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param array|string $method
     * @param array|string $value
     * @param \Exception $throw
     *
     * @return AbstractManager
     */
    private function getHackedManager($method, $value, $throw = null)
    {
        if (!is_array($method)) {
            $method = [$method];
            $value = [$value];
        }
        foreach ($method as $k => $m) {
            if (!is_null($throw)) {
                $this->stub->method($m)->will($this->throwException($throw));
            } else {
                $this->stub->method($m)->will($this->returnValue($value[$k]));
            }
        }
        /** @var Medoo $mockedMedoo */
        $mockedMedoo = $this->stub;
        $manager = new AbstractManager($mockedMedoo);
        $this->setProtectedProperty($manager, 'entityName', '\\Zergular\\Common\\AbstractEntity')
            ->setProtectedProperty($manager, 'tableName', 'testTable');

        return $manager;
    }

    /**
     * @param object $class
     * @param string $property
     * @param mixed $value
     *
     * @return $this
     */
    private function setProtectedProperty($class, $property, $value)
    {
        $reflection = new \ReflectionClass($class);
        $reflection_property = $reflection->getProperty($property);
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($class, $value);
        return $this;
    }

    /**
     * @param array $res
     * @param int $id
     *
     * @dataProvider dataProvider
     */
    public function testGetById($res, $id)
    {
        $manager = $this->getHackedManager('select', $res);
        $entity = $manager->getById($id);

        $this->assertEquals($id, $entity->getId());
        $this->assertEquals($res[0], $entity->toArray([]));
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            [
                [['id' => 1, 'created' => '2017-06-11 22:03:00', 'updated' => '2017-07-05 05:00:30']], 1
            ],
            [
                [['id' => 2, 'created' => '2017-06-11 22:03:00', 'updated' => '2017-07-05 05:00:30']], 2
            ]
        ];
    }

    /**
     * @param int $count
     *
     * @dataProvider countDataProvider
     */
    public function testGetCounts($count)
    {
        $manager = $this->getHackedManager('count', $count);
        $res = $manager->getCounts(['id' => $count]);

        $this->assertEquals($count, $res);
    }

    /**
     * @return array
     */
    public function countDataProvider()
    {
        return [
            [1],
            [5]
        ];
    }

    /**
     * @param array $res
     * @param int $id
     *
     * @dataProvider dataProvider
     */
    public function testGetOne($res, $id)
    {
        $manager = $this->getHackedManager('get', $res[0]);
        $entity = $manager->getOne($id);

        $this->assertEquals($id, $entity->getId());
        $this->assertEquals($res[0], $entity->toArray([]));
    }

    /**
     * @param array $res
     * @param int $id
     *
     * @dataProvider dataProvider
     */
    public function testGetAll($res, $id)
    {
        $manager = $this->getHackedManager('select', $res);
        /** @var EntityInterface[] $entities */
        $entities = $manager->getAll($id);

        foreach ($entities as $entity) {
            $this->assertEquals($id, $entity->getId());
            $this->assertEquals($res[0], $entity->toArray([]));
        }
    }

    /**
     * @param array $res
     * @param int $id
     *
     * @dataProvider dataProvider
     */
    public function testSave($res, $id)
    {
        $data = $res[0];
        $entity = new AbstractEntity;
        $entity->setId($data['id'])
            ->setCreated($data['created'])
            ->setUpdated($data['updated']);

        $manager = $this->getHackedManager(['count', 'update'], [1, $res]);
        $update = $manager->save($entity);

        $this->assertNotEquals($data['updated'], $update->getUpdated());
        $this->assertEquals($id, $update->getId());

        $manager = $this->getHackedManager(['count', 'insert', 'id'], [1, 1, $id]);
        $entity->setId(NULL);
        $this->assertNull($entity->getId());

        $insert = $manager->save($entity);
        $this->assertEquals($id, $insert->getId());
        $this->assertNotEquals($data['created'], $insert->getCreated());
        $this->assertInstanceOf(EntityInterface::class, $insert);

        $manager = $this->getHackedManager('count', 1, new \Exception('test'));
        $saveFails = $manager->save($entity);
        $this->assertNull($saveFails);
    }

    /**
     * @param array $res
     *
     * @dataProvider extractProvider
     */
    public function testExtractEntity($res)
    {
        $manager = $this->getHackedManager('select', $res);
        $extract = $manager->getById($res[0]['id']);
        $this->assertNull($extract);
    }

    /**
     * @return array
     */
    public function extractProvider()
    {
        return [
            [
                [
                    ['notExists' => 1, 'id' => 2]
                ]
            ]
        ];
    }

    /**
     * @param \stdClass $data
     *
     * @dataProvider deleteProvider
     */
    public function testDelete($data)
    {
        $manager = $this->getHackedManager('delete', $data);
        $delete = $manager->delete([]);
        $this->assertEquals($data, $delete);
    }

    /**
     * @return array
     */
    public function deleteProvider()
    {
        return [
            [new \stdClass()]
        ];
    }
}
