<?php

namespace API\Modules\DataServices\Controllers;

use API\Enums\MimeType;
use API\Managers\WorkspaceManager;
use API\Modules\DataServices\Managers\DataServiceOfferManager;
use API\Modules\DataServices\Models\DataServiceOffer;
use API\StaticClasses\Utils;
use Core\API;
use Core\Controller;
use Core\HttpResponseStatusCodes;

class DataServiceOfferController extends Controller
{
    private WorkspaceManager $workspaceManager;
    private DataServiceOfferManager $dataServiceOfferManager;

    public function __construct()
    {
        global $systemEntityManager;
        $this->workspaceManager = new WorkspaceManager($systemEntityManager);
        $this->dataServiceOfferManager = new DataServiceOfferManager($systemEntityManager);
    }

    public function index(string $workspaceId): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $query = "hasWorkspace==\"{$workspace->id}\"";
        $dataServiceOffers = $this->dataServiceOfferManager->readMultiple($query);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_OK);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataServiceOffers, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function store(string $workspaceId): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $data = API::request()->getDecodedJsonBody();

        $dataServiceOffer = new DataServiceOffer($data);
        $dataServiceOffer->id = Utils::generateUniqueNgsiLdUrn(DataServiceOffer::TYPE);

        $this->dataServiceOfferManager->create($dataServiceOffer);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_CREATED);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataServiceOffer, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function show(string $workspaceId, string $id): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $dataServiceOffer = $this->dataServiceOfferManager->readOne($id);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_OK);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataServiceOffer, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function update(string $workspaceId, string $id): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $dataServiceOffer = $this->dataServiceOfferManager->readOne($id);

        $data = API::request()->getDecodedJsonBody();

        $dataServiceOffer->update($data);

        $this->dataServiceOfferManager->update($dataServiceOffer);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_OK);
        API::response()->setHeader("Content-Type", MimeType::Json->value);
        API::response()->setJsonBody($dataServiceOffer, JSON_UNESCAPED_SLASHES);
        API::response()->send();
    }

    public function destroy(string $workspaceId, string $id): void
    {
        $workspace = $this->workspaceManager->readOne($workspaceId);

        $dataServiceOffer = $this->dataServiceOfferManager->readOne($id);

        $this->dataServiceOfferManager->delete($dataServiceOffer);

        API::response()->setStatusCode(HttpResponseStatusCodes::HTTP_NO_CONTENT);
        API::response()->send();
    }
}
