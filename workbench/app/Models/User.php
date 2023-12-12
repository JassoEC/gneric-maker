<?php

namespace Jassoec\GenericMaker\App\Models;

use Illuminate\Foundation\Auth\User as AuthUser;

class User extends AuthUser
{
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password'
    ];
}
