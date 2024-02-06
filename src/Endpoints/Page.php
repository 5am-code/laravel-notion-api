<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\Collections\EntityCollection;
use FiveamCode\LaravelNotionApi\Entities\Collections\UserCollection;
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
    public function property(string $propertyId): Property|EntityCollection
    {
        $response = $this->get(
            $this->url(Endpoint::PAGES . '/' . $this->pageId . '/' . 'properties' . '/' . rawurlencode(rawurldecode($propertyId)))
        );

        $rawContent = $response->json();

        if($rawContent['object'] === 'list'){
            if(count($rawContent['results']) === 0) return new EntityCollection();

            $type = $rawContent['results'][0]['type'];

            return match($type){
                'people' => new UserCollection($rawContent),
                default => new EntityCollection($rawContent)
            };
        }

        return Property::fromObjectResponse(
            id: $propertyId,
            rawContent: $rawContent
        );
    }
}
