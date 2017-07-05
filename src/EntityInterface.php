<?php

namespace Zergular\Common;

/**
 * Interface EntityInterface
 * @package Zergular\Common
 */
interface EntityInterface
{

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     *
     * @return EntityInterface
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getCreated();

    /**
     * @param string $created
     *
     * @return EntityInterface
     */
    public function setCreated($created);
    
    /**
     * @return string
     */
    public function getUpdated();
    
    /**
     * @param string $updated
     *
     * @return EntityInterface
     */
    public function setUpdated($updated);
    
    /**
     * @param string[] $skipFields
     *
     * @return array
     */
    public function toArray($skipFields = ['id']);
}
