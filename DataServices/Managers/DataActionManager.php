<?php

namespace API\Modules\DataServices\Managers;

use API\Managers\EntityManager;
use API\Modules\DataServices\Models\DataAction;

class DataActionManager
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(DataAction $dataAction): void
    {
        $entity = $dataAction->toEntity();
        $this->entityManager->create($entity);
    }

    public function readOne(string $id): DataAction
    {
        $entity = $this->entityManager->readOne($id);
        $dataAction = new DataAction();
        $dataAction->fromEntity($entity);
        return $dataAction;
    }

    public function readMultiple(?string $query = null, bool $idAsKey = false): array
    {
        $entities = $this->entityManager->readMultiple(null, DataAction::TYPE, $query);

        $dataActions = [];
        foreach ($entities as $entity) {
            $dataAction = new DataAction();
            $dataAction->fromEntity($entity);
            if ($idAsKey) $dataActions[$dataAction->id] = $dataAction;
            else $dataActions[] = $dataAction;
        }

        return $dataActions;
    }

    public function update(DataAction $dataAction): void
    {
        $entity = $dataAction->toEntity();
        $this->entityManager->update($entity);
    }

    public function delete(DataAction $dataAction): void
    {
        $entity = $dataAction->toEntity();
        $this->entityManager->delete($entity);
    }
}
