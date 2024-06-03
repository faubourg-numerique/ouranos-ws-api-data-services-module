<?php

namespace API\Modules\DataServices\Controllers;

use API\Enums\MimeType;
use API\Managers\PropertyManager;
use API\Managers\TypeManager;
use API\Managers\WorkspaceManager;
use API\Modules\DSC\Managers\AuthorizationRegistryGrantManager;
use API\Modules\DSC\Managers\AuthorizationRegistryManager;
use API\Modules\DataServices\Managers\DataActionManager;
use API\Modules\DataServices\Managers\DataServiceAccessManager;
use API\Modules\DataServices\Managers\DataServiceActionManager;
use API\Modules\DataServices\Managers\DataServiceManager;
use API\Modules\DataServices\Managers\DataServicePropertyManager;
use API\Modules\DataServices\Models\DataServiceAccess;
use API\Modules\DSC\Models\DelegationEvidence;
use API\Modules\DSC\Proxies\AuthorizationRegistryProxy;
use API\StaticClasses\Utils;
use Core\API;
use Core\Controller;
use Core\HttpResponseStatusCodes;

class DataServiceAccessController extends Controller
{
    private WorkspaceManager $workspaceManager;
    private PropertyManager $propertyManager;
    private TypeManager $typeManager;
    private DataActionManager $dataActionManager;
    private DataServiceManager $dataServiceManager;
    private DataServiceActionManager $dataServiceActionManager;
    private DataServicePropertyManager $dataServicePropertyManager;
    private DataServiceAccessManager $dataServiceAccessManager;
    private AuthorizationRegistryManager $authorizationRegistryManager;
    private AuthorizationRegistryGrantManager $authorizationRegistryGrantManager;

    public function __construct()
    {
        global $systemEntityManager;
        $this->workspaceManager = new WorkspaceManager($systemEntityManager);
        $this->propertyManager = new PropertyManager($systemEntityManager);
        $this->typeManager = new TypeManager($systemEntityManager);
        $this->dataActionManager = new DataActionManager($systemEntityManager);
        $this->dataServiceManager = new DataServiceManager($systemEntityManager);
        $this->dataServiceActionManager = new DataServiceActionManager($systemEntityManager);
        $this->dataServicePropertyManager = new DataServicePropertyManager($systemEntityManager);
        $this->dataServiceAccessManager = new DataServiceAccessManager($systemEntityManager);
        $this->authorizationRegistryManager = new AuthorizationRegistryManager($systemEntityManager);
        $this->authorizationRegistryGrantManager = new AuthorizationRegistryGrantManager($systemEntityManager);
    }

    public function index(string $workspaceId): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $query = "hasWorkspace==\"{$workspace->id}\"";
        $dataServiceAccesses = $this->dataServiceAccessManager->readMultiple($query);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_OK);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataServiceAccesses, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function store(string $workspaceId): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $data = API::request()->getDecodedJsonBody();

        $dataServiceAccess = new DataServiceAccess($data);
        $dataServiceAccess->id = Utils::generateUniqueNgsiLdUrn(DataServiceAccess::TYPE);

        $this->dataServiceAccessManager->create($dataServiceAccess);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_CREATED);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataServiceAccess, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function show(string $workspaceId, string $id): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $dataServiceAccess = $this->dataServiceAccessManager->readOne($id);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_OK);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataServiceAccess, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function update(string $workspaceId, string $id): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $dataServiceAccess = $this->dataServiceAccessManager->readOne($id);

        $data = API::request()->getDecodedJsonBody();

        $dataServiceAccess->update($data);

        $this->dataServiceAccessManager->update($dataServiceAccess);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_OK);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataServiceAccess, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function destroy(string $workspaceId, string $id): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $dataServiceAccess = $this->dataServiceAccessManager->readOne($id);

        $this->dataServiceAccessManager->delete($dataServiceAccess);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_NO_CONTENT);
        API::response()->send();
    }
}
