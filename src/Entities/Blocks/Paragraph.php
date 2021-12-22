<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

/**
 * Class Paragraph
 * @package FiveamCode\LaravelNotionApi\Entities\Blocks
 */
class Paragraph extends TextBlock
{
    public static function create($textContent): Paragraph
    {
        self::assertValidTextContent($textContent);

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
