<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

use FiveamCode\LaravelNotionApi\Entities\PropertyItems\RichText;

/**
 * Class TextBlock
 * @package FiveamCode\LaravelNotionApi\Entities\Blocks
 */
class TextBlock extends Block
{
    /**
     *
     */
    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();
        $this->fillContent();
    }

    /**
     *
     */
    protected function fillContent(): void
    {
        $this->content = new RichText($this->rawContent['text']);
        $this->text = $this->getContent()->getPlainText();
    }

    /**
     * @return RichText
     */
    public function getContent(): RichText
    {
        return $this->content;
    }
}
