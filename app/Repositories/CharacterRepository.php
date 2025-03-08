<?php

namespace App\Repositories;

use App\Interfaces\CharacterRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use NickBeen\RickAndMortyPhpApi\Character;

class CharacterRepository implements CharacterRepositoryInterface
{
    public function __construct(private Character $character)
    {
    }

    public function getAll(?int $page): array
    {
        $key = __CLASS__ . '::' . __FUNCTION__ . '::page::' . $page;

        try {
            $characters = Cache::get($key, fn () => $this->character->page($page)->get());

            return [
                'status' => 'success',
                'characters' => $characters,
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'characters' => []
            ];
        }
    }

    public function getById(int $id): array
    {
        $key = __CLASS__ . '::' . __FUNCTION__ . '::id::' . $id;

        try {
            $character = Cache::get($key, fn () => $this->character->get($id));

            return [
                'status' => 'success',
                'character' => $character,
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'character' => []
            ];
        }
    }
}
