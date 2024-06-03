<?php

namespace API\Modules\DataServices\Managers;

use API\Managers\EntityManager;
use API\Modules\DataServices\Models\DataServiceAccess;

class DataServiceAccessManager
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(DataServiceAccess $dataServiceAccess): void
    {
        $entity = $dataServiceAccess->toEntity();
        $this->entityManager->create($entity);
    }

    public function readOne(string $id): DataServiceAccess
    {
        $entity = $this->entityManager->readOne($id);
        $dataServiceAccess = new DataServiceAccess();
        $dataServiceAccess->fromEntity($entity);
        return $dataServiceAccess;
    }

    public function readMultiple(?string $query = null, bool $idAsKey = false): array
    {
        $entities = $this->entityManager->readMultiple(null, DataServiceAccess::TYPE, $query);

        $dataServiceAccesses = [];
        foreach ($entities as $entity) {
            $dataServiceAccess = new DataServiceAccess();
            $dataServiceAccess->fromEntity($entity);
            if ($idAsKey) $dataServiceAccesses[$dataServiceAccess->id] = $dataServiceAccess;
            else $dataServiceAccesses[] = $dataServiceAccess;
        }

        return $dataServiceAccesses;
    }

    public function update(DataServiceAccess $dataServiceAccess): void
    {
        $entity = $dataServiceAccess->toEntity();
        $this->entityManager->update($entity);
    }

    public function delete(DataServiceAccess $dataServiceAccess): void
    {
        $entity = $dataServiceAccess->toEntity();
        $this->entityManager->delete($entity);
    }
}
