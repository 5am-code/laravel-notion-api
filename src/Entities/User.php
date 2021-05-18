<?php

namespace FiveamCode\LaravelNotionApi\Entities;

use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;

class User extends Entity
{
    private string $name;
    private string $avatarUrl;

    protected function setResponseData(array $responseData): void
    {
        parent::setResponseData($responseData);
        if ($responseData['object'] !== 'user') throw HandlingException::instance("invalid json-array: the given object is not a user");
        $this->fillFromRaw();
    }

    private function fillFromRaw(): void
    {
        $this->fillId();
        $this->fillName();
        $this->fillAvatarUrl();
    }

    private function fillName(): void
    {
        if (Arr::exists($this->responseData, 'name') && $this->responseData['name'] !== null) {
            $this->name = $this->responseData['name'];
        }
    }

    private function fillAvatarUrl(): void
    {
        if (Arr::exists($this->responseData, 'avatar_url') && $this->responseData['avatar_url'] !== null) {
            $this->avatarUrl = $this->responseData['avatar_url'];
        }
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
    }
}
