<?php

namespace Jassoec\GenericMaker\App\Contracts;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface GenericApiServiceContract
{
    public function create(array $data): ?Model;

    public function update(string $id, array $data): ?Model;

    public function delete(string $id): bool;

    public function readOne(string $id, array $with = []): ?Model;

    public function readAll(array $where = []): ?Collection;

    public function getPaginatedData(
        ?string $search = '',
        ?int $perPage = 10,
        ?int $page = 1,
    ): ?Paginator;
}
