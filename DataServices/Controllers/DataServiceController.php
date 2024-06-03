<?php

namespace API\Modules\DataServices\Controllers;

use API\Enums\MimeType;
use API\Managers\WorkspaceManager;
use API\Modules\DataServices\Managers\DataServiceManager;
use API\Modules\DataServices\Models\DataService;
use API\StaticClasses\Utils;
use Core\API;
use Core\Controller;
use Core\HttpResponseStatusCodes;

class DataServiceController extends Controller
{
    private WorkspaceManager $workspaceManager;
    private DataServiceManager $dataServiceManager;

    public function __construct()
    {
        global $systemEntityManager;
        $this->workspaceManager = new WorkspaceManager($systemEntityManager);
        $this->dataServiceManager = new DataServiceManager($systemEntityManager);
    }

    public function index(string $workspaceId): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $query = "hasWorkspace==\"{$workspace->id}\"";
        $dataServices = $this->dataServiceManager->readMultiple($query);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_OK);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataServices, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function store(string $workspaceId): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $data = API::request()->getDecodedJsonBody();

        $dataService = new DataService($data);
        $dataService->id = Utils::generateUniqueNgsiLdUrn(DataService::TYPE);

        $this->dataServiceManager->create($dataService);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_CREATED);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataService, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function show(string $workspaceId, string $id): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $dataService = $this->dataServiceManager->readOne($id);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_OK);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataService, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function update(string $workspaceId, string $id): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $dataService = $this->dataServiceManager->readOne($id);

        $data = API::request()->getDecodedJsonBody();

        $dataService->update($data);

        $this->dataServiceManager->update($dataService);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_OK);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataService, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function destroy(string $workspaceId, string $id): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $dataService = $this->dataServiceManager->readOne($id);

        $this->dataServiceManager->delete($dataService);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_NO_CONTENT);
        API::response()->send();
    }
}
