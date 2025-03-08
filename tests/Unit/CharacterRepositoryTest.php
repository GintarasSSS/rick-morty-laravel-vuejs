<?php

namespace Tests\Unit;

use App\Repositories\CharacterRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Mockery;
use NickBeen\RickAndMortyPhpApi\Character;
use Tests\TestCase;

class CharacterRepositoryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->characterMock = Mockery::mock(Character::class);
    }

    public function testGetAllReturnsCharacters(): void
    {
        $this->characterMock->shouldReceive('page')->andReturnSelf();
        $this->characterMock->shouldReceive('get')->andReturn(['character1', 'character2']);

        Cache::shouldReceive('get')
            ->once()
            ->with(Mockery::any(), Mockery::type('Closure'))
            ->andReturn(['character1', 'character2']);

        $repository = new CharacterRepository($this->characterMock);
        $result = $repository->getAll(1);

        $this->assertEquals('success', $result['status']);
        $this->assertCount(2, $result['characters']);
    }

    public function testGetAllHandlesException(): void
    {
        $this->characterMock->shouldReceive('page')->andReturnSelf();
        $this->characterMock->shouldReceive('get')->andThrow(new \Exception('Test Exception'));

        Log::shouldReceive('error')->once()->with('Test Exception');

        $repository = new CharacterRepository($this->characterMock);
        $result = $repository->getAll(1);

        $this->assertEquals('error', $result['status']);
        $this->assertEmpty($result['characters']);
    }


    public function testGetByIdReturnsCharacter(): void
    {
        $this->characterMock->shouldReceive('get')->andReturn(['id' => 1, 'name' => 'John Doe']);

        Cache::shouldReceive('get')
            ->once()
            ->with(Mockery::any(), Mockery::type('Closure'))
            ->andReturn(['id' => 1, 'name' => 'John Doe']);

        $repository = new CharacterRepository($this->characterMock);
        $result = $repository->getById(1);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals('John Doe', $result['character']['name']);
    }

    public function testGetByIdHandlesException(): void
    {
        $this->characterMock->shouldReceive('get')->andThrow(new \Exception('Test Exception'));

        Log::shouldReceive('error')->once()->with('Test Exception');

        $repository = new CharacterRepository($this->characterMock);
        $result = $repository->getById(1);

        $this->assertEquals('error', $result['status']);
        $this->assertEmpty($result['character']);
    }
}
