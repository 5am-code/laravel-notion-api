<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\Properties\Property;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
use FiveamCode\LaravelNotionApi\Notion;

/**
 * Class Page.
 */
class Page extends Endpoint
{
    /**
     * @var ?string
     */
    private ?string $pageId = null;

    public function __construct(Notion $notion, string $pageId)
    {
        parent::__construct($notion);
        $this->pageId = $pageId;
    }

    /**
     * Retrieve a page property item.
     *
     * @url https://api.notion.com/{version}/pages/{page_id}/properties/{property_id} [get]
     *
     * @reference https://developers.notion.com/reference/retrieve-a-page-property.
     *
     * @param  string  $propertyId
     * @return Page
     *
     * @throws HandlingException
     * @throws NotionException
     */
    public function property(string $propertyId): Property
    {
        $response = $this->get(
            $this->url(Endpoint::PAGES.'/'.$this->pageId.'/'.'properties'.'/'.urlencode($propertyId))
        );

        return Property::fromResponse(
            propertyKey: null,
            rawContent: $response->json()
        );
    }
}
