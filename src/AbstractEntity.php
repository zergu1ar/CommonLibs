<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 01.07.17
 * Time: 12:50
 */

namespace Zergular\Common;

use Zergular\Common\Exception\UnknownMethod;

class AbstractEntity
{
    /** @var int */
    protected $id;
    /** @var string */
    protected $created;
    /** @var string */
    protected $updated;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param string $created
     *
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return string
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param string $updated
     *
     * @return $this
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @param string $method
     * @param mixed $args
     * @return mixed
     *
     * @throws UnknownMethod
     */
    public function __call($method, $args)
    {
        throw new UnknownMethod($method);
    }

    /**
     * @param string[] $skipFields
     *
     * @return array
     */
    public function toArray($skipFields = ['id'])
    {
        return array_diff_key(get_object_vars($this), array_flip($skipFields));
    }
}