<?php

namespace Zergular\Common;

use Medoo\Medoo;

/**
 * Class AbstractManager
 * @package Zergular\Common
 */
class AbstractManager
{
    /** @var Medoo */
    protected $persister;
    /** @var string */
    protected $tableName;
    /** @var string */
    protected $entityName;

    /**
     * @param Medoo $db
     */
    public function __construct(Medoo $db)
    {
        $this->persister = $db;
    }

    /**
     * @inheritdoc
     */
    public function getById($id)
    {
        return $this->extractEntity(
            $this->persister->select($this->tableName, '*', ['id' => $id])[0]
        );
    }

    /**
     * @param array $data
     *
     * @throws UnknownMethodException
     * @return AbstractEntity
     */
    protected function extractEntity($data)
    {
        try {
            if (is_array($data)) {
                $entity = new $this->entityName;
                foreach ($data as $key => $value) {
                    $entity->{'set' . ucfirst($key)}($value);
                }
                return $entity;
            }
        } catch (\Exception $e) {
            error_log($e->getMessage(), E_USER_WARNING);
        }
        return NULL;
    }

    /**
     * @inheritdoc
     */
    public function save(EntityInterface $entity)
    {
        $id = $entity->getId();
        $time = $this->getDateTime();
        $entity->setUpdated($time);
        try {
            if ($id && $this->persister->count($this->tableName, ['id' => $id])) {
                $this->persister->update($this->tableName, $entity->toArray(), ['id' => $id]);
            } else {
                $entity->setCreated($time);
                $this->persister->insert($this->tableName, $entity->toArray([]));
                $entity->setId($this->persister->id());
            }
        } catch (\Exception $e) {
            error_log($e->getMessage(), E_USER_WARNING);
            return NULL;
        }
        return $entity;
    }

    /**
     * @return false|string
     */
    protected function getDateTime()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * @inheritdoc
     */
    public function getCounts($cond = [])
    {
        return $this->persister->count($this->tableName, $cond);
    }

    /**
     * @inheritdoc
     */
    public function getAll($cond = [])
    {
        $results = [];
        $data = $this->persister->select($this->tableName, '*', $cond);
        foreach ($data as $row) {
            $entity = $this->extractEntity($row);
            if ($entity) {
                $results[] = $entity;
            }
        }
        return $results;
    }

    /**
     * @inheritdoc
     */
    public function getOne($cond = [])
    {
        return $this->extractEntity(
            $this->persister->get(
                $this->tableName,
                '*',
                $cond
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function delete($cond = [])
    {
        return $this->persister->delete($this->tableName, $cond);
    }
}
