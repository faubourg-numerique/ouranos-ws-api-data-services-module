<?php

namespace API\Modules\DataServices\Controllers;

use API\Enums\MimeType;
use API\Modules\DataServices\Managers\DataActionManager;
use API\Modules\DataServices\Models\DataAction;
use API\StaticClasses\Utils;
use Core\API;
use Core\Controller;
use Core\HttpResponseStatusCodes;

class DataActionController extends Controller
{
    private DataActionManager $dataActionManager;

    public function __construct()
    {
        global $systemEntityManager;
        $this->dataActionManager = new DataActionManager($systemEntityManager);
    }

    public function index(): void
    {
        $dataActions = $this->dataActionManager->readMultiple();

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_OK);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataActions, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function store(): void
    {
        $data = API::request()->getDecodedJsonBody();

        $dataAction = new DataAction($data);
        $dataAction->id = Utils::generateUniqueNgsiLdUrn(DataAction::TYPE);

        $this->dataActionManager->create($dataAction);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_CREATED);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataAction, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function show(string $id): void
    {
        $dataAction = $this->dataActionManager->readOne($id);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_OK);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataAction, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function update(string $id): void
    {
        $dataAction = $this->dataActionManager->readOne($id);

        $data = API::request()->getDecodedJsonBody();

        $dataAction->update($data);

        $this->dataActionManager->update($dataAction);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_OK);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataAction, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function destroy(string $id): void
    {
        $dataAction = $this->dataActionManager->readOne($id);

        $this->dataActionManager->delete($dataAction);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_NO_CONTENT);
        API::response()->send();
    }
}
