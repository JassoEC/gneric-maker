<?php

namespace Jassoec\GenericMaker\App\Repositories;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Jassoec\GenericMaker\App\Contracts\EloquentRepositoryContract;

abstract class BaseEloquentRepository implements EloquentRepositoryContract
{
    public function __construct(protected Model $model)
    {
    }
    public function findOne(string $id, array $where = [], array $with = []): ?Model
    {
        return $this->model
            ->with($with)
            ->where($where)
            ->find($id);
    }

    public function findAll(array $where = [], array $with = []): ?Collection
    {
        return $this->model
            ->with($with)
            ->where($where)
            ->get();
    }

    public function create(array $data): ?Model
    {
        $newModel = $this->model->newInstance();

        $newModel
            ->fill($data)
            ->save();

        return $newModel->fresh();
    }

    public function update(string $id, array $data): ?Model
    {
        $model = $this->model->find($id);

        if ($model) {
            $model->fill($data)->save();
            return $model->fresh();
        }

        return null;
    }

    public function delete(string $id): bool
    {
        return $this->model->destroy($id) > 0;
    }

    public function findOneBy(array $where = [], array $with = []): ?Model
    {
        return $this->model
            ->with($with)
            ->where($where)
            ->first();
    }

    public function getPaginatedData(
        ?string $search = '',
        array $searchableFields = [],
        ?int $perPage = 10,
        ?int $page = 1,
        array $where = [],
        array $with = [],
        array $orderBy = []
    ): ?Paginator {
        $query = $this->model
            ->with($with)
            ->where($where);

        if ($search) {
            $query->where(function ($query) use ($search, $searchableFields) {
                foreach ($searchableFields as $field) {
                    $query->orWhere($field, 'LIKE', "%{$search}%");
                }
            });
        }

        foreach ($orderBy as $field => $direction) {
            $query->orderBy($field, $direction);
        }

        return $query->simplePaginate(
            perPage: $perPage,
            page: $page,
            columns: ['*'],
            pageName: 'page'
        );
    }
}
