<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

/**
 * Class BulletedListItem
 * @package FiveamCode\LaravelNotionApi\Entities\Blocks
 */
class BulletedListItem extends TextBlock
{
    public static function create(array|string $textContent): BulletedListItem
    {
        $bulletedListItem = new BulletedListItem();
        TextBlock::createTextBlock($bulletedListItem, $textContent);
        return $bulletedListItem;
    }

    function __construct(array $responseData = null)
    {
        $this->type = "bulleted_list_item";
        parent::__construct($responseData);
    }
}
