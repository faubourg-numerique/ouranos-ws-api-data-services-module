<?php

namespace API\Modules\DataServices\Managers;

use API\Managers\EntityManager;
use API\Modules\DataServices\Models\DataServiceAction;

class DataServiceActionManager
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(DataServiceAction $dataServiceAction): void
    {
        $entity = $dataServiceAction->toEntity();
        $this->entityManager->create($entity);
    }

    public function readOne(string $id): DataServiceAction
    {
        $entity = $this->entityManager->readOne($id);
        $dataServiceAction = new DataServiceAction();
        $dataServiceAction->fromEntity($entity);
        return $dataServiceAction;
    }

    public function readMultiple(?string $query = null, bool $idAsKey = false): array
    {
        $entities = $this->entityManager->readMultiple(null, DataServiceAction::TYPE, $query);

        $dataServiceActions = [];
        foreach ($entities as $entity) {
            $dataServiceAction = new DataServiceAction();
            $dataServiceAction->fromEntity($entity);
            if ($idAsKey) $dataServiceActions[$dataServiceAction->id] = $dataServiceAction;
            else $dataServiceActions[] = $dataServiceAction;
        }

        return $dataServiceActions;
    }

    public function update(DataServiceAction $dataServiceAction): void
    {
        $entity = $dataServiceAction->toEntity();
        $this->entityManager->update($entity);
    }

    public function delete(DataServiceAction $dataServiceAction): void
    {
        $entity = $dataServiceAction->toEntity();
        $this->entityManager->delete($entity);
    }
}
