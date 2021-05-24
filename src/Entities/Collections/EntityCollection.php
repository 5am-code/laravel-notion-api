<?php

namespace FiveamCode\LaravelNotionApi\Entities\Collections;


use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use FiveamCode\LaravelNotionApi\Entities\Page;
use FiveamCode\LaravelNotionApi\Entities\Entity;
use FiveamCode\LaravelNotionApi\Entities\Database;
use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;


class EntityCollection
{
    protected array $responseData = [];
    protected array $rawResults = [];
    protected Collection $collection;

    public function __construct(array $responseData = null)
    {
        $this->setResponseData($responseData);
    }

    protected function setResponseData(array $responseData): void
    {
        // TODO
        // Currently, the API returns not-found objects with status code 200 -
        // so we have to check here on the given status code in the paylaod,
        // if the object was not found.
        if (
            array_key_exists('object', $responseData)
            && $responseData['object'] === 'error'
            && Arr::exists($responseData, 'status') && $responseData['status'] === 404
        ) {
            throw NotionException::instance("Not found", compact("responseData"));
        }

        if (!Arr::exists($responseData, 'object')) throw HandlingException::instance("invalid json-array: no object given");
        if (!Arr::exists($responseData, 'results')) throw HandlingException::instance("invalid json-array: no results given");
        if ($responseData['object'] !== 'list') throw HandlingException::instance("invalid json-array: the given object is not a list");

        $this->responseData = $responseData;
        $this->fillFromRaw();
        $this->collectChildren();
    }

    protected function collectChildren(): void
    {
        $this->collection = new Collection();
        foreach ($this->rawResults as $pageChild) {
            if (Arr::exists($pageChild, 'object')) {
                if ($pageChild['object'] == 'page') $this->collection->add(new Page($pageChild));
                if ($pageChild['object'] == 'database') $this->collection->add(new Database($pageChild));
            }
        }
    }

    protected function fillFromRaw()
    {
        $this->fillResult();
    }

    protected function fillResult()
    {
        $this->rawResults = $this->responseData['results'];
    }


    public function getRawResponse(): array
    {
        return $this->responseData;
    }

    public function asCollection(): Collection
    {
        return $this->collection;
    }

    public function asJson(): string
    {
        return $this->collection->map(function (Entity $item) {
            return $item->toArray();
        });
    }
}
