<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

use DateTime;
use Illuminate\Support\Arr;
use FiveamCode\LaravelNotionApi\Entities\Entity;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

/**
 * Class HeadingThree
 * @package FiveamCode\LaravelNotionApi\Entities\Blocks
 */
class HeadingThree extends TextBlock
{
    public static function create(array|string $textContent): HeadingThree
    {
        $headingThree = new HeadingThree();
        HeadingThree::createTextBlock($headingThree, $textContent);
        return $headingThree;
    }

    function __construct(array $responseData = null)
    {
        $this->type = "heading_3";
        parent::__construct($responseData);
    }
}
