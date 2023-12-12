<?php

namespace Jassoec\GenericMaker\Tests\Unit\app\Repositories;

use Illuminate\Database\Eloquent\Model;
use Jassoec\GenericMaker\App\Contracts\EloquentRepositoryContract;
use Jassoec\GenericMaker\App\Models\User;
use Jassoec\GenericMaker\App\Repositories\BaseEloquentRepository;
use Jassoec\GenericMaker\Tests\TestCase;
use Mockery;

class BaseEloquentRepositoryTest extends TestCase
{
    private EloquentRepositoryContract $repository;


    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFaker();

        $this->repository = new class(new User()) extends BaseEloquentRepository
        {
        };
    }

    public function testCanReadModel(): void
    {
        $user = User::create([
            'name' => $this->faker()->name(),
            'email' => $this->faker()->email(),
            'password' => $this->faker()->password(10)
        ]);

        $this->assertInstanceOf(
            Model::class,
            $this->repository->readOne($user->id)
        );
    }
}
