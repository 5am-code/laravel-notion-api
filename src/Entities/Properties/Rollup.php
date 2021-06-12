<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use DateTime;
use FiveamCode\LaravelNotionApi\Entities\PropertyItems\RichDate;
use Illuminate\Support\Collection;

/**
 * Class Rollup
 * @package FiveamCode\LaravelNotionApi\Entities\Properties
 */
class Rollup extends Property
{
    protected string $rollupType;

    /**
     *
     */
    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();

        $this->rollupType = $this->rawContent['type'];

        if ($this->rollupType == 'number') {
            $this->content = $this->rawContent[$this->rollupType];
        } else if ($this->rollupType == 'date') {
            $this->content = new RichDate();
            if (isset($this->rawContent[$this->rollupType]['start'])) $this->content->setStart(new DateTime($this->rawContent[$this->rollupType]['start']));
            if (isset($this->rawContent[$this->rollupType]['end'])) $this->content->setEnd(new DateTime($this->rawContent[$this->rollupType]['end']));
        } else if ($this->rollupType == 'array') {
            $this->content = new Collection();
            foreach ($this->rawContent[$this->rollupType] as $rollupPropertyItem) {
                $rollupPropertyItem['id'] = 'undefined';
                $this->content->add(Property::fromResponse("", $rollupPropertyItem));
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
    public function getRollupType(): string
    {
        return $this->rollupType;
    }

    /**
     * @return string
     */
    public function getRollupContentType(): string
    {
        if($this->getContent() instanceof Collection){
            $firstItem = $this->getContent()->first();
            if($firstItem == null) return "null";
            return $firstItem->getType();
        }
        else{
            return $this->getRollupType();
        }
    }
}
