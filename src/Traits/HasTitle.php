<?php

namespace FiveamCode\LaravelNotionApi\Traits;

use FiveamCode\LaravelNotionApi\Entities\PropertyItems\RichText;
use Illuminate\Support\Arr;

/**
 * Trait HasTitle.
 */
trait HasTitle
{
    /**
     * @var array
     */
    protected array $responseData = [];

    /**
     * @var string
     */
    protected string $title = '';

    /**
     * @var ?RichText
     */
    protected ?RichText $richTitle = null;

    protected function fillTitleAttributes(): void
    {
        $this->fillTitle();
    }

    private function fillTitle(): void
    {
        if (Arr::exists($this->responseData, 'title') && is_array($this->responseData['title'])) {
            $this->title = Arr::first($this->responseData['title'], null, ['plain_text' => ''])['plain_text'];
            $this->richTitle = new RichText($this->responseData['title']);
        }
    }

    public function setTitle($title): self
    {
        $this->title = $title;
        $this->responseData['title'] = [
            [
                'type' => 'text',
                'text' => [
                    'content' => $title
                ]
            ]
        ];
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
