<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

use DateTime;
use Illuminate\Support\Arr;
use FiveamCode\LaravelNotionApi\Entities\Entity;
use FiveamCode\LaravelNotionApi\Entities\PropertyItems\RichText;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

/**
 * Class Paragraph
 * @package FiveamCode\LaravelNotionApi\Entities\Blocks
 */
class Embed extends Block
{
    private RichText $caption;
    private string $url = "";

    public static function create(string $url, string $caption): Embed
    {
        $embed = new Embed();

        $embed->rawContent = [
            'url' => $url,
            'caption' => [
                [
                    'type' => 'text',
                    'text' => [
                        'content' => $caption
                    ]
                ]
            ]
        ];

        return $embed;
    }

    function __construct(array $responseData = null)
    {
        $this->type = "embed";
        parent::__construct($responseData);
    }

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
        $this->url = $this->rawContent['url'];
        $this->caption = new RichText($this->rawContent['caption']);
        $this->content = $this->url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getCaption()
    {
        return $this->caption;
    }
}
