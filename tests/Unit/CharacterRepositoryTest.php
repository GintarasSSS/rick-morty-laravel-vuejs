<?php

namespace Tests\Unit;

use App\Repositories\CharacterRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Mockery;
use NickBeen\RickAndMortyPhpApi\Character;
use NickBeen\RickAndMortyPhpApi\Episode;
use Tests\TestCase;

class CharacterRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->characterMock = Mockery::mock(Character::class);
        $this->episodeMock = Mockery::mock(Episode::class);

        Carbon::setTestNow(Carbon::create(2000));
    }

    public function testGetAllReturnsCharacters(): void
    {
        $this->characterMock->shouldReceive('page')->with(1)->andReturnSelf();
        $this->characterMock->shouldReceive('get')->andReturn(['character1', 'character2']);

        Cache::shouldReceive('remember')
            ->once()
            ->with(Mockery::any(), Mockery::any(), Mockery::type('Closure'))
            ->andReturn(['character1', 'character2']);

        $repository = new CharacterRepository($this->characterMock, $this->episodeMock);
        $result = $repository->getAll(1);

        $this->assertEquals('success', $result['status']);
        $this->assertCount(2, $result['characters']);
    }

    public function testGetAllWithNullPageReturnsCharacters(): void
    {
        $this->characterMock->shouldReceive('page')->with(null)->andReturnSelf();
        $this->characterMock->shouldReceive('get')->andReturn(['character1', 'character2']);

        Cache::shouldReceive('remember')
            ->once()
            ->with(Mockery::any(), Mockery::any(), Mockery::type('Closure'))
            ->andReturn(['character1', 'character2']);

        $repository = new CharacterRepository($this->characterMock, $this->episodeMock);
        $result = $repository->getAll(null);

        $this->assertEquals('success', $result['status']);
        $this->assertCount(2, $result['characters']);
    }

    public function testGetAllHandlesException(): void
    {
        $this->characterMock->shouldReceive('page')->andReturnSelf();
        $this->characterMock->shouldReceive('get')->andThrow(new \Exception('Test Exception'));

        Log::shouldReceive('error')->once()->with('Test Exception');

        $repository = new CharacterRepository($this->characterMock, $this->episodeMock);
        $result = $repository->getAll(1);

        $this->assertEquals('error', $result['status']);
        $this->assertEmpty($result['characters']);
    }

    public function testGetByIdReturnsCharacter(): void
    {
        $episodes = [
            ['id' => 1, 'name' => 'Test One'],
            ['id' => 2, 'name' => 'Test Two']
        ];

        $this->characterMock->shouldReceive('get')->with(1)->andReturn((object) [
            'id' => 1,
            'name' => 'John Doe',
            'episode' => [
                'https://test.com/api/test/1',
                'https://test.com/api/test/2'
            ]
        ]);

        $this->episodeMock->shouldReceive('get')->with(1, 2)->andReturn($episodes);

        Cache::shouldReceive('remember')
            ->once()
            ->with(Mockery::any(), Mockery::any(), Mockery::type('Closure'))
            ->andReturn((object) [
                'id' => 1,
                'name' => 'John Doe',
                'episodes_extended' => $episodes
            ]);

        $repository = new CharacterRepository($this->characterMock, $this->episodeMock);
        $result = $repository->getById(1);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals('John Doe', $result['character']->name);
        $this->assertCount(2, $result['character']->episodes_extended);
    }

    public function testGetByIdWithDifferentIdReturnsCharacter(): void
    {
        $episode = [['id' => 3, 'name' => 'Test Test One']];

        $this->characterMock->shouldReceive('get')->with(2)->andReturn((object) [
            'id' => 2,
            'name' => 'Jane Doe',
            'episode' => [
                'https://test.com/api/test/3'
            ]
        ]);

        $this->episodeMock->shouldReceive('get')->with(3)->andReturn($episode);

        Cache::shouldReceive('remember')
            ->once()
            ->with(Mockery::any(), Mockery::any(), Mockery::type('Closure'))
            ->andReturn((object) [
                'id' => 2,
                'name' => 'Jane Doe',
                'episodes_extended' => $episode
            ]);

        $repository = new CharacterRepository($this->characterMock, $this->episodeMock);
        $result = $repository->getById(2);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Jane Doe', $result['character']->name);
        $this->assertCount(1, $result['character']->episodes_extended);
    }

    public function testGetByIdHandlesException(): void
    {

        $this->characterMock->shouldReceive('get')->with(1)->andThrow(new \Exception('Test Exception'));

        Log::shouldReceive('error')->once()->with('Test Exception');

        $repository = new CharacterRepository($this->characterMock, $this->episodeMock);
        $result = $repository->getById(1);

        $this->assertEquals('error', $result['status']);
        $this->assertEmpty($result['character']);
    }

    public function testGetByIdReturnsCharacterWithoutEpisodes(): void
    {
        $this->characterMock->shouldReceive('get')->with(1)->andReturn((object) [
            'id' => 1,
            'name' => 'John Doe',
            'episode' => []
        ]);

        Cache::shouldReceive('remember')
            ->once()
            ->with(Mockery::any(), Mockery::any(), Mockery::type('Closure'))
            ->andReturn((object) [
                'id' => 1,
                'name' => 'John Doe',
                'episodes_extended' => []
            ]);

        $repository = new CharacterRepository($this->characterMock, $this->episodeMock);
        $result = $repository->getById(1);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals('John Doe', $result['character']->name);
        $this->assertEmpty($result['character']->episodes_extended);
    }

    public function testGetByIdHandlesInvalidEpisodeUrls(): void
    {
        $this->characterMock->shouldReceive('get')->with(1)->andReturn((object) [
            'id' => 1,
            'name' => 'John Doe',
            'episode' => [
                'https://test.com/api/test/invalid'
            ]
        ]);

        $this->episodeMock->shouldNotReceive('get');

        Cache::shouldReceive('remember')
            ->once()
            ->with(Mockery::any(), Mockery::any(), Mockery::type('Closure'))
            ->andReturn((object) [
                'id' => 1,
                'name' => 'John Doe',
                'episodes_extended' => []
            ]);

        $repository = new CharacterRepository($this->characterMock, $this->episodeMock);
        $result = $repository->getById(1);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals('John Doe', $result['character']->name);
        $this->assertEmpty($result['character']->episodes_extended);
    }

    public function testGetEpisodesReturnsEpisodes(): void
    {
        $episodes = [
            'https://test.com/api/test/1',
            'https://test.com/api/test/2'
        ];

        $this->episodeMock->shouldReceive('get')->with(1, 2)->andReturn([
            ['id' => 1, 'name' => 'Test One'],
            ['id' => 2, 'name' => 'Test Two']
        ]);

        $repository = new CharacterRepository($this->characterMock, $this->episodeMock);
        $result = $this->invokeMethod($repository, 'getEpisodes', [$episodes]);

        $this->assertCount(2, $result);
        $this->assertEquals('Test One', $result[0]['name']);
        $this->assertEquals('Test Two', $result[1]['name']);
    }

    public function testGetEpisodesHandlesInvalidUrls(): void
    {
        $episodes = ['https://test.com/api/test/invalid'];

        $this->episodeMock->shouldNotReceive('get');

        $repository = new CharacterRepository($this->characterMock, $this->episodeMock);
        $result = $this->invokeMethod($repository, 'getEpisodes', [$episodes]);

        $this->assertEmpty($result);
    }

    public function testGetEpisodesHandlesEmptyArray(): void
    {
        $episodes = [];

        $this->episodeMock->shouldNotReceive('get');

        $repository = new CharacterRepository($this->characterMock, $this->episodeMock);
        $result = $this->invokeMethod($repository, 'getEpisodes', [$episodes]);

        $this->assertEmpty($result);
    }

    private function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
