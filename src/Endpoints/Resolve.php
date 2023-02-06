<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\Blocks\Block;
use FiveamCode\LaravelNotionApi\Entities\Collections\CommentCollection;
use FiveamCode\LaravelNotionApi\Entities\Comment;
use FiveamCode\LaravelNotionApi\Entities\Database;
use FiveamCode\LaravelNotionApi\Entities\NotionParent;
use FiveamCode\LaravelNotionApi\Entities\Page;
use FiveamCode\LaravelNotionApi\Entities\Properties\Relation;
use FiveamCode\LaravelNotionApi\Entities\User;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
use FiveamCode\LaravelNotionApi\Notion;

/**
 * Class Resolve.
 */
class Resolve extends Endpoint
{
    /**
     * Block constructor.
     *
     * @param  Notion  $notion
     *
     * @throws HandlingException
     * @throws \FiveamCode\LaravelNotionApi\Exceptions\LaravelNotionAPIException
     */
    public function __construct(Notion $notion)
    {
        parent::__construct($notion);
    }

    /**
     * @param  User  $user
     * 
     * @return User
     * @throws HandlingException
     * @throws NotionException
     */
    public function user(User $user): User
    {
        return $this->notion->users()->find($user->getId());
    }

    /**
     * @param  NotionParent  $parent
     * 
     * @return Page|Database|Block
     * @throws HandlingException
     * @throws NotionException
     */
    public function parent(NotionParent $parent): Page|Database|Block
    {
        switch ($parent->getObjectType()) {
            case 'page_id':
                return $this->notion->pages()->find($parent->getId());
            case 'database_id':
                return $this->notion->databases()->find($parent->getId());
            case 'block_id':
                return $this->notion->block($parent->getId())->retrieve();
            case 'workspace_id':
                throw new HandlingException('A Notion Workspace cannot be resolved by the Notion API.');
            default:
                throw new HandlingException('Unknown parent type while resolving the notion parent');
        }
    }
}
