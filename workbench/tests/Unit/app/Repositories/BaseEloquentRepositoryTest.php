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

        $model = new User();

        $this->repository = new class($model) extends BaseEloquentRepository
        {
            public function __construct($model)
            {
                parent::__construct($model);
            }
        };
    }

    public function test_base_eloquent_repository_can_get_model_by_id(): void
    {
        $user = $this->buildUser();

        $this->assertInstanceOf(
            User::class,
            $this->repository->findOne($user->id)
        );
    }

    public function test_base_eloquent_repository_can_get_model_by_id_with_where(): void
    {
        $user = $this->buildUser();

        $this->assertInstanceOf(
            User::class,
            $this->repository->findOne($user->id, ['name' => $user->name])
        );
    }

    public function test_base_eloquent_repository_can_create_model(): void
    {
        $data = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(),
        ];

        $this->assertInstanceOf(
            User::class,
            $this->repository->create($data)
        );

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }

    public function test_base_eloquent_repository_can_update_model(): void
    {
        $user = $this->buildUser();

        $data = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
        ];

        $this->assertInstanceOf(
            User::class,
            $this->repository->update($user->id, $data)
        );

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }


    public function test_base_eloquent_repository_can_delete_model(): void
    {
        $user = $this->buildUser();

        $this->assertTrue(
            $this->repository->delete($user->id)
        );

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
