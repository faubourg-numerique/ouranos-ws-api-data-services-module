<?php

namespace API\Modules\DataServices\Managers;

use API\Managers\EntityManager;
use API\Modules\DataServices\Models\DataService;

class DataServiceManager
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(DataService $dataService): void
    {
        $entity = $dataService->toEntity();
        $this->entityManager->create($entity);
    }

    public function readOne(string $id): DataService
    {
        $entity = $this->entityManager->readOne($id);
        $dataService = new DataService();
        $dataService->fromEntity($entity);
        return $dataService;
    }

    public function readMultiple(?string $query = null, bool $idAsKey = false): array
    {
        $entities = $this->entityManager->readMultiple(null, DataService::TYPE, $query);

        $dataServices = [];
        foreach ($entities as $entity) {
            $dataService = new DataService();
            $dataService->fromEntity($entity);
            if ($idAsKey) $dataServices[$dataService->id] = $dataService;
            else $dataServices[] = $dataService;
        }

        return $dataServices;
    }

    public function update(DataService $dataService): void
    {
        $entity = $dataService->toEntity();
        $this->entityManager->update($entity);
    }

    public function delete(DataService $dataService): void
    {
        $entity = $dataService->toEntity();
        $this->entityManager->delete($entity);
    }
}
