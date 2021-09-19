<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

/**
 * Class Paragraph
 * @package FiveamCode\LaravelNotionApi\Entities\Blocks
 */
class Pdf extends BaseFileBlock
{
    public static function create(string $url, string $caption = ""): Pdf
    {
        $pdf = new Pdf();
        BaseFileBlock::createFileBlock($pdf, $url, $caption);
        return $pdf;
    }

    function __construct(array $responseData = null)
    {
        $this->type = "pdf";
        parent::__construct($responseData);
    }

}
