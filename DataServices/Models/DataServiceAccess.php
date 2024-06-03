<?php

namespace API\Modules\DataServices\Models;

use API\Models\Entity;
use API\Traits\Updatable;
use Core\Model;

class DataServiceAccess extends Model
{
    use Updatable;

    const TYPE = "DataServiceAccess";

    public string $id;
    public string $hasDataService;
    public string $hasRole;
    public string $hasWorkspace;

    public function toEntity(): Entity
    {
        $entity = new Entity();
        $entity->setId($this->id);
        $entity->setType(self::TYPE);
        $entity->setRelationship("hasDataService", $this->hasDataService);
        $entity->setRelationship("hasRole", $this->hasRole);
        $entity->setRelationship("hasWorkspace", $this->hasWorkspace);
        return $entity;
    }

    public function fromEntity(Entity $entity): void
    {
        $this->id = $entity->getId();
        $this->hasDataService = $entity->getRelationship("hasDataService");
        $this->hasRole = $entity->getRelationship("hasRole");
        $this->hasWorkspace = $entity->getRelationship("hasWorkspace");
    }
}
