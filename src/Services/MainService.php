<?php

namespace App\Services;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\LongLivedAccessToken;
use AmoCRM\Collections\CustomFields\CustomFieldsCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\CustomFields\MultiselectCustomFieldModel;
use App\Actions\MakeMultiSelectFieldModelAction;
use App\Helpers\MakeSomeLeadCollectionHelper;
use Symfony\Component\Dotenv\Dotenv;

class MainService
{
    private const BATCH_SIZE = 50;
    private const CREATE_COUNT = 1000;
    private AmoCRMApiClient $apiClient;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct()
    {
        $dotEnv = new Dotenv();
        $dotEnv->load('.env');

        $accessToken = new LongLivedAccessToken($_ENV['ACCESS_TOKEN']);

        $this->apiClient = (new AmoCRMApiClient())->setAccessToken($accessToken)
            ->setAccountBaseDomain($_ENV['BASE_DOMAIN']);
    }

    /**
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiException
     * @throws AmoCRMMissedTokenException
     */
    public function scriptPartA(): array
    {
        $idsForUpdate = [];

        $batchCount = self::CREATE_COUNT / self::BATCH_SIZE;
        for ($i = 0; $i < $batchCount; $i++) {
            $leads = MakeSomeLeadCollectionHelper::run(self::BATCH_SIZE);

            $response = $this->apiClient->leads()->addComplex($leads);
            $idsForUpdate[] = $response->pluck('id');
            unset($leads, $response);
        }

        return array_merge(...$idsForUpdate);
    }

    /**
     * @throws InvalidArgumentException
     * @throws AmoCRMApiException
     * @throws AmoCRMMissedTokenException
     * @throws AmoCRMoAuthApiException
     */
    public function scriptPartB(): MultiselectCustomFieldModel
    {
        $fieldsCollection = new CustomFieldsCollection();

        $multiSelectField = MakeMultiSelectFieldModelAction::run(
            'Мульти-список',
            'MULTI-LIST',
            ['1 вариант', '2 вариант', '3 вариант']
        );
        $fieldsCollection->add($multiSelectField);

        $customFieldsService = $this->apiClient->customFields(EntityTypesInterface::LEADS);

        /** @var MultiselectCustomFieldModel $field */
        $field = $customFieldsService->add($fieldsCollection)->first();

        return $field;
    }

    /**
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiException
     * @throws AmoCRMMissedTokenException
     */
    public function scriptPartC(array $ids, MultiselectCustomFieldModel $field): void
    {
        $customFields = [
            [
                'field_id' => $field->getId(),
                'field_code' => null,
                'field_name' => null,
                'field_type' => 'multiselect',
                'values' =>
                    [
                        [
                            'value' => null,
                            'enum_id' => $field->getEnums()?->first()?->getId(),
                        ],
                    ],
            ],
        ];

        foreach (array_chunk($ids, self::BATCH_SIZE) as $idsToUpdate) {
            $toCollection = [];
            while ($id = array_pop($idsToUpdate)) {
                $toCollection[] = ['id' => $id, 'custom_fields_values' => $customFields];
            }

            $collection = LeadsCollection::fromArray($toCollection);
            $this->apiClient->leads()->update($collection);
        }
    }
}
