<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use DateTime;
use FiveamCode\LaravelNotionApi\Entities\PropertyItems\RichDate;

/**
 * Class Formula
 * @package FiveamCode\LaravelNotionApi\Entities\Properties
 */
class Formula extends Property
{
    protected string $formulaType;

    /**
     *
     */
    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();

        if (array_key_exists('type', $this->rawContent)) {
            $this->formulaType = $this->rawContent['type'];

            if ($this->formulaType == 'string' || $this->formulaType == 'number' || $this->formulaType == 'boolean') {
                $this->content = $this->rawContent[$this->formulaType];
            } else if ($this->formulaType == 'date') {
                $this->content = new RichDate();
                if (isset($this->rawContent[$this->formulaType]['start'])) $this->content->setStart(new DateTime($this->rawContent[$this->formulaType]['start']));
                if (isset($this->rawContent[$this->formulaType]['end'])) $this->content->setEnd(new DateTime($this->rawContent[$this->formulaType]['end']));
            }
        }
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getFormulaType(): string
    {
        return $this->formulaType;
    }
}
