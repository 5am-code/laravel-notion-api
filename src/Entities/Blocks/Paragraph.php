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
class Paragraph extends TextBlock
{
    public static function create(array|string $textContent): Paragraph
    {
        $paragraph = new Paragraph();
        TextBlock::createTextBlock($paragraph, $textContent);
        return $paragraph;
    }

    function __construct(array $responseData = null)
    {
        $this->type = "paragraph";
        parent::__construct($responseData);
    }
}
