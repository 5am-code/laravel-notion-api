<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

/**
 * Class Quote.
 */
class Quote extends TextBlock
{
    public static function create($textContent): Quote
    {
        self::assertValidTextContent($textContent);

        $quote = new Quote();
        TextBlock::createTextBlock($quote, $textContent);

        return $quote;
    }

    public function __construct(array $responseData = null)
    {
        $this->type = 'quote';
        parent::__construct($responseData);
    }
}
