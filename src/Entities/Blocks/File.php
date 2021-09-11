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
class File extends BaseFileBlock
{
    public static function create(string $url, string $caption = ""): File
    {
        $file = new File();
        BaseFileBlock::createFileBlock($file, $url, $caption);
        return $file;
    }

    function __construct(array $responseData = null)
    {
        $this->type = "file";
        parent::__construct($responseData);
    }

}
