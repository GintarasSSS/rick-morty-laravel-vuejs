<?php

namespace Tests\Feature;

use App\Repositories\CharacterRepository;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CharacterControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = Mockery::mock(CharacterRepository::class);
    }

    public function testIndexReturnsCharacters(): void
    {
        $data = [
            'status' => 'success',
            'characters' => ['character1', 'character2']
        ];

        $this->repositoryMock->shouldReceive('getAll')
            ->with(1)
            ->andReturn($data);

        $this->app->instance(CharacterRepository::class, $this->repositoryMock);

        $response = $this->getJson('/api/characters?page=1');

        $response->assertStatus(Response::HTTP_OK)->assertJson($data);
    }

    public function testShowReturnsCharacter(): void
    {
        $data = [
            'status' => 'success',
            'characters' => ['character1', 'character2']
        ];

        $this->repositoryMock->shouldReceive('getById')
            ->with(1)
            ->andReturn($data);

        $this->app->instance(CharacterRepository::class, $this->repositoryMock);

        $response = $this->getJson('/api/characters/1');

        $response->assertStatus(Response::HTTP_OK)->assertJson($data);
    }

    public function testShowHandlesNonExistentCharacter(): void
    {
        $data = [
            'status' => 'error',
            'character' => []
        ];

        $this->repositoryMock->shouldReceive('getById')
            ->with(1)
            ->andReturn($data);

        $this->app->instance(CharacterRepository::class, $this->repositoryMock);

        $response = $this->getJson('/api/characters/1');

        $response->assertStatus(Response::HTTP_OK)->assertJson($data);
    }
}
