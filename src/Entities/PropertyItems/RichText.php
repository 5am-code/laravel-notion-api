<?php

namespace FiveamCode\LaravelNotionApi\Entities\PropertyItems;

use FiveamCode\LaravelNotionApi\Entities\Entity;
use Illuminate\Support\Arr;

class RichText extends Entity
{
    protected string $plainText = "";

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
