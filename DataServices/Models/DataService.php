<?php

namespace API\Modules\DataServices\Models;

use API\Models\Entity;
use API\Traits\Updatable;
use Core\Model;

class DataService extends Model
{
    use Updatable;

    const TYPE = "DataService";

    public string $id;
    public ?string $name = null;
    public bool $isDemand;
    public bool $isOffer;
    public string $hasEntityType;
    public string $hasWorkspace;

    public function toEntity(): Entity
    {
        $entity = new Entity();
        $entity->setId($this->id);
        $entity->setType(self::TYPE);
        if (!is_null($this->name)) {
            $entity->setProperty("name", $this->name);
        }
        $entity->setProperty("isDemand", $this->isDemand);
        $entity->setProperty("isOffer", $this->isOffer);
        $entity->setRelationship("hasEntityType", $this->hasEntityType);
        $entity->setRelationship("hasWorkspace", $this->hasWorkspace);
        return $entity;
    }

    public function fromEntity(Entity $entity): void
    {
        $this->id = $entity->getId();
        if ($entity->propertyExists("name")) {
            $this->name = $entity->getProperty("name");
        }
        $this->isDemand = $entity->getProperty("isDemand");
        $this->isOffer = $entity->getProperty("isOffer");
        $this->hasEntityType = $entity->getRelationship("hasEntityType");
        $this->hasWorkspace = $entity->getRelationship("hasWorkspace");
    }
}
