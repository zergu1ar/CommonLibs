<?php

namespace Zergular\Common\Tests;

use Zergular\Common\Config;

/**
 * Class ConfigTest
 * @package Zergular\Common\Tests
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $dir
     * @param \Exception $result
     *
     * @dataProvider setDirData
     */
    public function testSetDir($dir, $result)
    {
        Config::setDir($dir);
        $this->expectException($result);
        Config::get('db');
    }

    /**
     * @return array
     */
    public function setDirData()
    {
        return [
            ['', \Exception::class]
        ];
    }

    /**
     * @param string $dir
     * @param string $type
     * @param string $key
     *
     * @dataProvider getDirData
     */
    public function testGetDir($dir, $type, $key)
    {
        $file = $dir . $type . '.ini';
        if (file_exists($file)) {
            unlink($file);
        }
        Config::setDir($dir);
        $res = Config::get($type);
        $this->assertArrayHasKey($key, $res);
        $res = Config::get($type);
        $this->assertArrayHasKey($key, $res);
        unlink($file);
    }

    /**
     * @return array
     */
    public function getDirData()
    {
        return [
            [__DIR__ . '/log', 'redis', 'scheme'],
            [__DIR__ . '/log', 'db', 'database_type']
        ];
    }

}
