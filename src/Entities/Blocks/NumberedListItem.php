<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

use DateTime;
use Illuminate\Support\Arr;
use FiveamCode\LaravelNotionApi\Entities\Entity;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

/**
 * Class NumberedListItem
 * @package FiveamCode\LaravelNotionApi\Entities\Blocks
 */
class NumberedListItem extends TextBlock
{
    public static function create(array|string $textContent): NumberedListItem
    {
        $numberedListItem = new NumberedListItem();    
        TextBlock::createTextBlock($numberedListItem, $textContent);
        return $numberedListItem;
    }

    function __construct(array $responseData = null){
        $this->type = "numbered_list_item";
        parent::__construct($responseData);
    }
}
