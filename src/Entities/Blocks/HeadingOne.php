<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

/**
 * Class HeadingOne
 * @package FiveamCode\LaravelNotionApi\Entities\Blocks
 */
class HeadingOne extends TextBlock
{
    public static function create(array|string $textContent): HeadingOne
    {
        $headingOne = new HeadingOne();
        TextBlock::createTextBlock($headingOne, $textContent);
        return $headingOne;
    }

    function __construct(array $responseData = null)
    {
        $this->type = "heading_1";
        parent::__construct($responseData);
    }
}
