<?php

namespace Jassoec\GenericMaker\App\Repositories;

use Jassoec\GenericMaker\App\Models\User;

class UserRepository extends BaseEloquentRepository
{
    public function __construct()
    {
        parent::__construct(new User());
    }
}
