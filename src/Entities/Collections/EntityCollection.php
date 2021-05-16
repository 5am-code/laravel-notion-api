<?php

namespace FiveamCode\LaravelNotionApi\Entities\Collections;

use FiveamCode\LaravelNotionApi\Entities\Database;
use FiveamCode\LaravelNotionApi\Entities\Entity;
use FiveamCode\LaravelNotionApi\Entities\Page;
use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;


class EntityCollection
{
    protected array $responseData = [];
    protected array $rawResults = [];
    protected Collection $collection;

    public function __construct(array $reponseData = null)
    {
        $this->setResponseData($reponseData);
    }

    protected function setResponseData(array $reponseData): void
    {
        if (!Arr::exists($reponseData, 'object')) throw WrapperException::instance("invalid json-array: no object given");
        if (!Arr::exists($reponseData, 'results')) throw WrapperException::instance("invalid json-array: no results given");
        if ($reponseData['object'] !== 'list') throw WrapperException::instance("invalid json-array: the given object is not a list");

        $this->responseData = $reponseData;
        $this->fillFromRaw();
        $this->collectChildren();
    }

    protected function collectChildren(): void
    {
        $this->collection = new Collection();
        foreach ($this->rawResults as $pageChild) {
            if (Arr::exists($pageChild, 'object')) {
                if($pageChild['object'] == 'page') $this->collection->add(new Page($pageChild));
                if($pageChild['object'] == 'database') $this->collection->add(new Database($pageChild));
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


    public function getRaw(): array
    {
        return $this->responseData;
    }

    public function getRawResults(): array
    {
        return $this->rawResults;
    }

    public function getResults(): Collection
    {
        return $this->collection;
    }
}
