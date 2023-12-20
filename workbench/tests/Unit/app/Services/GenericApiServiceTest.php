<?php

namespace Tests\Unit\App\Services;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Jassoec\GenericMaker\App\Contracts\GenericApiServiceContract;
use Jassoec\GenericMaker\App\Repositories\UserRepository;
use Jassoec\GenericMaker\App\Services\GenericApiService;
use Jassoec\GenericMaker\Tests\TestCase;

class GenericApiServiceTest extends TestCase
{
    private GenericApiServiceContract $service;

    protected function setUp(): void
    {
        parent::setUp();

        $repository = new UserRepository();

        $this->service =  new class($repository) extends GenericApiService
        {
            public function __construct($repository)
            {
                parent::__construct($repository);
            }
        };
    }

    public function test_generic_api_service_can_create_model(): void
    {
        $data = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(),
        ];

        $this->assertInstanceOf(
            Model::class,
            $this->service->create($data)
        );


        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }


    public function test_generic_api_service_can_update_model(): void
    {
        $user = $this->service->create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(),
        ]);

        $data = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
        ];

        $this->assertInstanceOf(
            Model::class,
            $this->service->update($user->id, $data)
        );

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }

    public function test_generic_api_service_can_delete_model(): void
    {
        $user = $this->service->create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(),
        ]);

        $this->assertTrue(
            $this->service->delete($user->id)
        );

        $this->assertDatabaseMissing('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_generic_api_service_can_read_one_model(): void
    {
        $user = $this->service->create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(),
        ]);

        $this->assertInstanceOf(
            Model::class,
            $this->service->readOne($user->id)
        );
    }

    public function test_generic_api_service_can_read_all_model(): void
    {
        $this->assertInstanceOf(
            Collection::class,
            $this->service->readAll()
        );
    }

    public function test_generic_api_service_can_get_paginated_data(): void
    {
        $this->assertInstanceOf(
            Paginator::class,
            $this->service->getPaginatedData()
        );
    }
}
