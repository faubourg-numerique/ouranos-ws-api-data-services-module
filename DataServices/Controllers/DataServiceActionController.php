<?php

namespace API\Modules\DataServices\Controllers;

use API\Enums\MimeType;
use API\Managers\WorkspaceManager;
use API\Modules\DataServices\Managers\DataServiceActionManager;
use API\Modules\DataServices\Models\DataServiceAction;
use API\StaticClasses\Utils;
use Core\API;
use Core\Controller;
use Core\HttpResponseStatusCodes;

class DataServiceActionController extends Controller
{
    private WorkspaceManager $workspaceManager;
    private DataServiceActionManager $dataServiceActionManager;

    public function __construct()
    {
        global $systemEntityManager;
        $this->workspaceManager = new WorkspaceManager($systemEntityManager);
        $this->dataServiceActionManager = new DataServiceActionManager($systemEntityManager);
    }

    public function index(string $workspaceId): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $query = "hasWorkspace==\"{$workspace->id}\"";
        $dataServiceActions = $this->dataServiceActionManager->readMultiple($query);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_OK);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataServiceActions, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function store(string $workspaceId): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $data = API::request()->getDecodedJsonBody();

        $dataServiceAction = new DataServiceAction($data);
        $dataServiceAction->id = Utils::generateUniqueNgsiLdUrn(DataServiceAction::TYPE);

        $this->dataServiceActionManager->create($dataServiceAction);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_CREATED);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataServiceAction, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function show(string $workspaceId, string $id): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $dataServiceAction = $this->dataServiceActionManager->readOne($id);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_OK);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataServiceAction, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function update(string $workspaceId, string $id): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $dataServiceAction = $this->dataServiceActionManager->readOne($id);

        $data = API::request()->getDecodedJsonBody();

        $dataServiceAction->update($data);

        $this->dataServiceActionManager->update($dataServiceAction);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_OK);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataServiceAction, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function destroy(string $workspaceId, string $id): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $dataServiceAction = $this->dataServiceActionManager->readOne($id);

        $this->dataServiceActionManager->delete($dataServiceAction);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_NO_CONTENT);
        API::response()->send();
    }
}
