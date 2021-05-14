<?php

namespace FiveamCode\LaravelNotionApi\Entities;

use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;


//TODO:WORK IN PROGRESS
abstract class EntityCollection
{
    protected array $responseData = [];
    protected array $rawResults = [];
    protected Collection $collection;

    public function __construct(array $reponseData = null)
    {
        $this->setResponseData($reponseData);
    }

    protected abstract function collectChildren();

    protected function setResponseData(array $reponseData): void
    {
        if (!Arr::exists($reponseData, 'object')) throw WrapperException::instance("invalid json-array: no object given");
        if (!Arr::exists($reponseData, 'results')) throw WrapperException::instance("invalid json-array: no results given");
        if ($reponseData['object'] !== 'list') throw WrapperException::instance("invalid json-array: the given object is not a list");

        $this->responseData = $reponseData;
        $this->fillFromRaw();
    }

    protected function fillFromRaw()
    {
        $this->fillResult();
    }

    protected function fillResult()
    {
        $this->rawResults = $this->responseData['results'];
        $this->collectChildren();
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
