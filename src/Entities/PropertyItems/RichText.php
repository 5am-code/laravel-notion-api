<?php

namespace FiveamCode\LaravelNotionApi\Entities\PropertyItems;

use DateTime;
use FiveamCode\LaravelNotionApi\Entities\Entity;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class RichText extends Entity
{
    private string $plainText = "";

    public function __construct(array $responseData)
    {
        $this->setResponseData($responseData);
    }

    protected function setResponseData(array $responseData): void
    {
        $this->responseData = $responseData;
        $this->fillFromRaw();
    }

    protected function fillFromRaw(): void
    {
        $this->fillPlainText();
    }

    protected function fillPlainText(): void
    {
        if (is_array($this->responseData)) {
            foreach ($this->responseData as $textItem) {
                if (Arr::exists($textItem, 'plain_text')) {
                    $this->plainText .= $textItem['plain_text'];
                }
            }
        }
    }

    public function getPlainText(): string
    {
        return $this->plainText;
    }
}
