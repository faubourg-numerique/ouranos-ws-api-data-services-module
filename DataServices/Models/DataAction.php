<?php

namespace API\Modules\DataServices\Models;

use API\Models\Entity;
use API\Traits\Updatable;
use Core\Model;

class DataAction extends Model
{
    use Updatable;

    const TYPE = "DataAction";

    public string $id;
    public string $name;
    public ?string $description = null;

    public function toEntity(): Entity
    {
        $entity = new Entity();
        $entity->setId($this->id);
        $entity->setType(self::TYPE);
        $entity->setProperty("name", $this->name);
        if (!is_null($this->description)) {
            $entity->setProperty("description", $this->description);
        }
        return $entity;
    }

    public function fromEntity(Entity $entity): void
    {
        $this->id = $entity->getId();
        $this->name = $entity->getProperty("name");
        if ($entity->propertyExists("description")) {
            $this->description = $entity->getProperty("description");
        }
    }
}
