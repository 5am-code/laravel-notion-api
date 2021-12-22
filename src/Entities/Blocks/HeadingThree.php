<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

/**
 * Class HeadingThree.
 */
class HeadingThree extends TextBlock
{
    public static function create(array|string $textContent): HeadingThree
    {
        $headingThree = new HeadingThree();
        HeadingThree::createTextBlock($headingThree, $textContent);

        return $headingThree;
    }

    public function __construct(array $responseData = null)
    {
        $this->type = 'heading_3';
        parent::__construct($responseData);
    }
}
