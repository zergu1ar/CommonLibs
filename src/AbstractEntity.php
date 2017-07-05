<?php

namespace Zergular\Common;

/**
 * Class AbstractEntity
 * @package Zergular\Common
 */
class AbstractEntity implements EntityInterface
{
    /** @var int */
    protected $id;
    /** @var string */
    protected $created;
    /** @var string */
    protected $updated;

    /**
     * @param string $method
     * @param mixed $args
     * @return mixed
     *
     * @throws UnknownMethodException
     */
    public function __call($method, $args)
    {
        throw new UnknownMethodException($method);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @inheritdoc
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @inheritdoc
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function toArray($skipFields = ['id'])
    {
        return array_diff_key(get_object_vars($this), array_flip($skipFields));
    }
}
