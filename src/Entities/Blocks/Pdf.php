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
