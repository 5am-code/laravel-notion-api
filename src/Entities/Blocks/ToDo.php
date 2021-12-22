<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

/**
 * Class ToDo
 * @package FiveamCode\LaravelNotionApi\Entities\Blocks
 */
class ToDo extends TextBlock
{
    public static function create($textContent): ToDo
    {
        self::assertValidTextContent($textContent);

        $toDo = new ToDo();
        TextBlock::createTextBlock($toDo, $textContent);
        return $toDo;
    }

    function __construct(array $responseData = null)
    {
        $this->type = "to_do";
        parent::__construct($responseData);
    }
}
