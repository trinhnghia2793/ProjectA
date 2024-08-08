<?php

namespace App\Repositories;

use App\Models\PostCatalogue;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface;
use App\Repositories\BaseRepository;

class PostCatalogueRepository extends BaseRepository implements PostCatalogueRepositoryInterface
{

    protected $model;

    public function __construct(
        PostCatalogue $model
    ){
        $this->model = $model;   
    }

}
