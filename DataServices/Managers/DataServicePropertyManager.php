<?php

namespace API\Modules\DataServices\Managers;

use API\Managers\EntityManager;
use API\Modules\DataServices\Models\DataServiceProperty;

class DataServicePropertyManager
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(DataServiceProperty $dataServiceProperty): void
    {
        $entity = $dataServiceProperty->toEntity();
        $this->entityManager->create($entity);
    }

    public function readOne(string $id): DataServiceProperty
    {
        $entity = $this->entityManager->readOne($id);
        $dataServiceProperty = new DataServiceProperty();
        $dataServiceProperty->fromEntity($entity);
        return $dataServiceProperty;
    }

    public function readMultiple(?string $query = null, bool $idAsKey = false): array
    {
        $entities = $this->entityManager->readMultiple(null, DataServiceProperty::TYPE, $query);

        $dataServicePropertys = [];
        foreach ($entities as $entity) {
            $dataServiceProperty = new DataServiceProperty();
            $dataServiceProperty->fromEntity($entity);
            if ($idAsKey) $dataServicePropertys[$dataServiceProperty->id] = $dataServiceProperty;
            else $dataServicePropertys[] = $dataServiceProperty;
        }

        return $dataServicePropertys;
    }

    public function update(DataServiceProperty $dataServiceProperty): void
    {
        $entity = $dataServiceProperty->toEntity();
        $this->entityManager->update($entity);
    }

    public function delete(DataServiceProperty $dataServiceProperty): void
    {
        $entity = $dataServiceProperty->toEntity();
        $this->entityManager->delete($entity);
    }
}
