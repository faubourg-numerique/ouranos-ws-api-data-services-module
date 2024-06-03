<?php

namespace API\Modules\DataServices\Controllers;

use API\Enums\MimeType;
use API\Managers\WorkspaceManager;
use API\Modules\DataServices\Managers\DataServicePropertyManager;
use API\Modules\DataServices\Models\DataServiceProperty;
use API\StaticClasses\Utils;
use Core\API;
use Core\Controller;
use Core\HttpResponseStatusCodes;

class DataServicePropertyController extends Controller
{
    private WorkspaceManager $workspaceManager;
    private DataServicePropertyManager $dataServicePropertyManager;

    public function __construct()
    {
        global $systemEntityManager;
        $this->workspaceManager = new WorkspaceManager($systemEntityManager);
        $this->dataServicePropertyManager = new DataServicePropertyManager($systemEntityManager);
    }

    public function index(string $workspaceId): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $query = "hasWorkspace==\"{$workspace->id}\"";
        $dataServicePropertys = $this->dataServicePropertyManager->readMultiple($query);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_OK);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataServicePropertys, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function store(string $workspaceId): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $data = API::request()->getDecodedJsonBody();

        $dataServiceProperty = new DataServiceProperty($data);
        $dataServiceProperty->id = Utils::generateUniqueNgsiLdUrn(DataServiceProperty::TYPE);

        $this->dataServicePropertyManager->create($dataServiceProperty);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_CREATED);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataServiceProperty, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function show(string $workspaceId, string $id): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $dataServiceProperty = $this->dataServicePropertyManager->readOne($id);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_OK);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataServiceProperty, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function update(string $workspaceId, string $id): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $dataServiceProperty = $this->dataServicePropertyManager->readOne($id);

        $data = API::request()->getDecodedJsonBody();

        $dataServiceProperty->update($data);

        $this->dataServicePropertyManager->update($dataServiceProperty);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_OK);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataServiceProperty, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function destroy(string $workspaceId, string $id): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $dataServiceProperty = $this->dataServicePropertyManager->readOne($id);

        $this->dataServicePropertyManager->delete($dataServiceProperty);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_NO_CONTENT);
        API::response()->send();
    }
}
