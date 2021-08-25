<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

use DateTime;
use Illuminate\Support\Arr;
use FiveamCode\LaravelNotionApi\Entities\Entity;
use FiveamCode\LaravelNotionApi\Entities\PropertyItems\RichText;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

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
