<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

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
