<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

use DateTime;
use Illuminate\Support\Arr;
use FiveamCode\LaravelNotionApi\Entities\Entity;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

/**
 * Class ToDo
 * @package FiveamCode\LaravelNotionApi\Entities\Blocks
 */
class ToDo extends TextBlock
{
    public static function create(array|string $textContent): ToDo
    {
        $toDo = new ToDo();    
        TextBlock::createTextBlock($toDo, $textContent);
        return $toDo;
    }

    function __construct(array $responseData = null){
        $this->type = "to_do";
        parent::__construct($responseData);
    }
}
