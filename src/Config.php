<?php

namespace Zergular\Common;

/**
 * Class Config
 * @package Zergular\Common
 */
class Config
{
    /** @var string */
    private static $dir;

    /**
     * @param string $path
     */
    public static function setDir($path)
    {
        self::$dir = $path;
    }

    /**
     * @uses Config::getDefaultRedis()
     * @uses Config::getDefaultDb()
     * @param string $type
     *
     * @return array
     */
    public static function get($type)
    {
        $path = self::getDirPath() . $type . '.ini';
        if (file_exists($path)) {
            return parse_ini_file($path);
        }
        $method = 'getDefault' . ucfirst($type);
        $config = self::$method();
        file_put_contents($path, self::createIniContent($config));
        return $config;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private static function getDirPath()
    {
        if (empty(self::$dir) || !is_dir(self::$dir)) {
            throw new \Exception('Dir is not specified');
        }
        return self::$dir;
    }

    /**
     * @return array
     */
    private static function getDefaultRedis()
    {
        return [
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 6379,
        ];
    }

    /**
     * @return array
     */
    private static function getDefaultDb()
    {
        return [
            'database_type' => 'mysql',
            'database_name' => '',
            'server' => '127.0.0.1',
            'charset' => 'utf8',
            'username' => '',
            'password' => ''
        ];
    }

    /**
     * @param array $config
     *
     * @return string
     */
    private static function createIniContent($config)
    {
        $configContent = '';
        foreach ($config as $key => $value) {
            $configContent .= "$key=$value\n";
        }
        return $configContent;
    }
}
