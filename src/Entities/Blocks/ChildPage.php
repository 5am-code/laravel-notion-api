<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

/**
 * Class ChildPage
 * @package FiveamCode\LaravelNotionApi\Entities\Blocks
 */
class ChildPage extends Block
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
        $this->content = $this->rawContent['title'];
        $this->text = $this->getContent();
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
