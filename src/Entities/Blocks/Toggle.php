<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

use DateTime;
use Illuminate\Support\Arr;
use FiveamCode\LaravelNotionApi\Entities\Entity;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

/**
 * Class Toggle
 * @package FiveamCode\LaravelNotionApi\Entities\Blocks
 */
class Toggle extends TextBlock
{
    public static function create(array|string $textContent): Toggle
    {
        $toggle = new Toggle();    
        TextBlock::createTextBlock($toggle, $textContent);
        return $toggle;
    }

    function __construct(array $responseData = null){
        $this->type = "toggle";
        parent::__construct($responseData);
    }
}
