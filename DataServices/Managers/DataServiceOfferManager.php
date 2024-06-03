<?php

namespace API\Modules\DataServices\Managers;

use API\Managers\EntityManager;
use API\Modules\DataServices\Models\DataServiceOffer;

class DataServiceOfferManager
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(DataServiceOffer $dataServiceOffer): void
    {
        $entity = $dataServiceOffer->toEntity();
        $this->entityManager->create($entity);
    }

    public function readOne(string $id): DataServiceOffer
    {
        $entity = $this->entityManager->readOne($id);
        $dataServiceOffer = new DataServiceOffer();
        $dataServiceOffer->fromEntity($entity);
        return $dataServiceOffer;
    }

    public function readMultiple(?string $query = null, bool $idAsKey = false): array
    {
        $entities = $this->entityManager->readMultiple(null, DataServiceOffer::TYPE, $query);

        $dataServiceOffers = [];
        foreach ($entities as $entity) {
            $dataServiceOffer = new DataServiceOffer();
            $dataServiceOffer->fromEntity($entity);
            if ($idAsKey) $dataServiceOffers[$dataServiceOffer->id] = $dataServiceOffer;
            else $dataServiceOffers[] = $dataServiceOffer;
        }

        return $dataServiceOffers;
    }

    public function update(DataServiceOffer $dataServiceOffer): void
    {
        $entity = $dataServiceOffer->toEntity();
        $this->entityManager->update($entity);
    }

    public function delete(DataServiceOffer $dataServiceOffer): void
    {
        $entity = $dataServiceOffer->toEntity();
        $this->entityManager->delete($entity);
    }
}
