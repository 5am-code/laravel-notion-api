<?php

namespace FiveamCode\LaravelNotionApi\Entities;

use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

/**
 * Class NotionParent.
 */
class NotionParent extends Entity
{
    /**
     * @param  array  $responseData
     *
     * @throws HandlingException
     * @throws \FiveamCode\LaravelNotionApi\Exceptions\NotionException
     */
    protected function setResponseData(array $responseData): void
    {
        parent::setResponseData($responseData);
        if (
            $responseData['object'] !== 'page_id'
            && $responseData['object'] !== 'database_id'
            && $responseData['object'] !== 'workspace_id'
            && $responseData['object'] !== 'block_id'
        ) {
            throw HandlingException::instance('invalid json-array: the given object is not a valid parent');
        }
        $this->fillFromRaw();
    }

    private function fillFromRaw(): void
    {
        parent::fillEssentials();
    }

    /**
     * @return bool
     */
    public function isBlock(): bool
    {
        return $this->getObjectType() === 'block_id';
    }

    /**
     * @return bool
     */
    public function isPage(): bool
    {
        return $this->getObjectType() === 'page_id';
    }

    /**
     * @return bool
     */
    public function isDatabase(): bool
    {
        return $this->getObjectType() === 'database_id';
    }

    /**
     * @return bool
     */
    public function isWorkspace(): bool
    {
        return $this->getObjectType() === 'workspace_id';
    }
}
