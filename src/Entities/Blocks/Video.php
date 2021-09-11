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
class Video extends BaseFileBlock
{
    public static function create(string $url, string $caption = ""): Video
    {
        $video = new Video();
        BaseFileBlock::createFileBlock($video, $url, $caption);
        return $video;
    }

    function __construct(array $responseData = null)
    {
        $this->type = "video";
        parent::__construct($responseData);
    }

}
