<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

/**
 * Class Toggle.
 */
class Toggle extends TextBlock
{
    public static function create(array|string $textContent): Toggle
    {
        $toggle = new Toggle();
        TextBlock::createTextBlock($toggle, $textContent);

        return $toggle;
    }

    public function __construct(array $responseData = null)
    {
        $this->type = 'toggle';
        parent::__construct($responseData);
    }
}
