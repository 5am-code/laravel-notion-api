<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

/**
 * Class NumberedListItem
 * @package FiveamCode\LaravelNotionApi\Entities\Blocks
 */
class NumberedListItem extends TextBlock
{
    public static function create($textContent): NumberedListItem
    {
        self::assertValidTextContent($textContent);

        $numberedListItem = new NumberedListItem();
        TextBlock::createTextBlock($numberedListItem, $textContent);
        return $numberedListItem;
    }

    function __construct(array $responseData = null)
    {
        $this->type = "numbered_list_item";
        parent::__construct($responseData);
    }
}
