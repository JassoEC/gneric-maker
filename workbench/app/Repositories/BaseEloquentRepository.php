<?php

namespace Jassoec\GenericMaker\App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Jassoec\GenericMaker\App\Contracts\EloquentRepositoryContract;

abstract class BaseEloquentRepository implements EloquentRepositoryContract
{
    public function __construct(protected Model $model)
    {
    }
    public function readOne(string $id): ?Model
    {
        return $this->model->find($id);
    }
}
