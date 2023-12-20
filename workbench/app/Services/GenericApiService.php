<?php

namespace Jassoec\GenericMaker\App\Services;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Jassoec\GenericMaker\App\Contracts\EloquentRepositoryContract;
use Jassoec\GenericMaker\App\Contracts\GenericApiServiceContract;

class GenericApiService implements GenericApiServiceContract
{
    public function __construct(
        protected   EloquentRepositoryContract $repository,
        protected array $storeRules = [],
        protected array $updateRules = [],
        protected array $storeMessages = [],
        protected array $updateMessages = [],
        protected array $where = [],
        protected array $with = [],
        protected array $orderBy = []
    ) {
    }

    public function create(array $data): ?Model
    {
        $this->validate($data, $this->storeRules, $this->storeMessages);

        $this->preCreate($data);

        return $this->repository->create($data);
    }

    public function update(string $id, array $data): ?Model
    {
        $this->validate($data, $this->updateRules, $this->updateMessages);

        $this->readOne($id);

        $this->preUpdate($data);

        return $this->repository->update($id, $data);
    }

    public function delete(string $id): bool
    {
        $this->readOne($id);

        return $this->repository->delete($id);
    }

    public function readOne(string $id, array $with = []): ?Model
    {
        $model = $this->repository->findOne($id, $with);

        if (!$model) {
            throw new ModelNotFoundException();
        }

        return $model;
    }

    public function readAll(array $where = []): ?Collection
    {
        return $this->repository->findAll($where);
    }

    protected function validate(array $data, array $validationRules = [], array $validationMessages = []): void
    {
        $validator = validator($data, $validationRules, $validationMessages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function getPaginatedData(?string $search = '', ?int $perPage = 10, ?int $page = 1): ?Paginator
    {
        return $this->repository->getPaginatedData(
            search: $search,
            perPage: $perPage,
            where: $this->where,
            with: $this->with,
            orderBy: $this->orderBy
        );
    }

    protected function preCreate(array $data): void
    {
    }

    protected function preUpdate(array $data): void
    {
    }
}
