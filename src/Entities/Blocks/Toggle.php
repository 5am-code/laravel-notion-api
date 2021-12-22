<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

/**
 * Class Toggle
 * @package FiveamCode\LaravelNotionApi\Entities\Blocks
 */
class Toggle extends TextBlock
{
    public static function create( $textContent): Toggle
    {
        self::assertValidTextContent($textContent);

        $toggle = new Toggle();
        TextBlock::createTextBlock($toggle, $textContent);
        return $toggle;
    }

    function __construct(array $responseData = null)
    {
        $this->type = "toggle";
        parent::__construct($responseData);
    }
}
