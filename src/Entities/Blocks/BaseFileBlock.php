<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

use DateTime;
use FiveamCode\LaravelNotionApi\Entities\Contracts\Modifiable;
use Illuminate\Support\Arr;
use FiveamCode\LaravelNotionApi\Entities\Entity;
use FiveamCode\LaravelNotionApi\Entities\PropertyItems\RichText;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

/**
 * Class TextBlock
 * @package FiveamCode\LaravelNotionApi\Entities\Blocks
 */
class BaseFileBlock extends Block implements Modifiable
{
    protected static final function createFileBlock(BaseFileBlock $fileBlock, string $url, string $caption = ""): BaseFileBlock
    {
        $fileBlock->rawContent = [
            'type' => 'external',
            'caption' => [
                [
                    'type' => 'text',
                    'text' => [
                        'content' => $caption
                    ]
                ]
            ],
            'external' => [
                'url' => $url,
            ]
        ];

        $fileBlock->fillContent();

        return $fileBlock;
    }

    private string $hostingType = "";
    private string $url = "";
    private RichText $caption;


    /**
     * 
     */
    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();
        $this->fillContent();
    }

    /**
     * 
     */
    protected function fillContent(): void
    {
        $this->hostingType = $this->rawContent['type'];
        $this->url = $this->rawContent[$this->hostingType]['url'];
        $this->caption = new RichText($this->rawContent['caption']);
        $this->content = $this->url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getHostingType()
    {
        return $this->hostingType;
    }

    public function getCaption()
    {
        return $this->caption;
    }
}
