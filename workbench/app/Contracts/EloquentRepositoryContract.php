<?php

namespace Jassoec\GenericMaker\App\Contracts;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryContract
{
    public function findOne(string $id, array $with = []): ?Model;

    public function findAll(array $where = [], array $with = []): ?Collection;

    public function create(array $data): ?Model;

    public function update(string $id, array $data): ?Model;

    public function delete(string $id): bool;

    public function findOneBy(array $where = [], array $with = []): ?Model;

    public function getPaginatedData(
        ?string $search,
        array $searchableFields = [],
        ?int $perPage = 10,
        ?int $page = 1,
        array $where = [],
        array $with = [],
        array $orderBy = []
    ): ?Paginator;
}
