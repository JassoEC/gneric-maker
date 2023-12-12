<?php

namespace Jassoec\GenericMaker\App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryContract
{
    public function readOne(string $id): ?Model;
}
