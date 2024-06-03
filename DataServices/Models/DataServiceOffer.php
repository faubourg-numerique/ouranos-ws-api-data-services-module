<?php

namespace API\Modules\DataServices\Models;

use API\Models\Entity;
use API\Traits\Updatable;
use Core\Model;

class DataServiceOffer extends Model
{
    use Updatable;

    const TYPE = "DataServiceOffer";

    public string $id;
    public string $description;
    public string $url;
    public string $hasDataService;
    public string $hasWorkspace;

    public function toEntity(): Entity
    {
        $entity = new Entity();
        $entity->setId($this->id);
        $entity->setType(self::TYPE);
        $entity->setProperty("description", $this->description);
        $entity->setProperty("url", $this->url);
        $entity->setRelationship("hasDataService", $this->hasDataService);
        $entity->setRelationship("hasWorkspace", $this->hasWorkspace);
        return $entity;
    }

    public function fromEntity(Entity $entity): void
    {
        $this->id = $entity->getId();
        $this->description = $entity->getProperty("description");
        $this->url = $entity->getProperty("url");
        $this->hasDataService = $entity->getRelationship("hasDataService");
        $this->hasWorkspace = $entity->getRelationship("hasWorkspace");
    }
}
