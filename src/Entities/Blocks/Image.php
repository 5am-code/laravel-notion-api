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
class Image extends BaseFileBlock
{
    public static function create(string $url, string $caption = ""): Image
    {
        $image = new Image();
        BaseFileBlock::createFileBlock($image, $url, $caption);
        return $image;
    }

    function __construct(array $responseData = null)
    {
        $this->type = "image";
        parent::__construct($responseData);
    }

}
