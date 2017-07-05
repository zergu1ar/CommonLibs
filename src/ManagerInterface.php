<?php

namespace Zergular\Common;

/**
 * Interface ManagerInterface
 * @package Zergular\Common
 */
interface ManagerInterface
{
    /**
     * @param int $id
     *
     * @return EntityInterface
     */
    public function getById($id);

    /**
     * @param EntityInterface $entity
     *
     * @return EntityInterface
     */
    public function save(EntityInterface $entity);

    /**
     * @param array $cond
     *
     * @return int
     */
    public function getCounts($cond = []);

    /**
     * @param array $cond
     *
     * @return EntityInterface[]
     */
    public function getAll($cond = []);

    /**
     * @param array $cond
     *
     * @return EntityInterface
     */
    public function getOne($cond = []);

    /**
     * @param array $cond
     * @return bool|\Traversable
     */
    public function delete($cond = []);
}
