<?php

namespace App\Repositories;

use App\Interfaces\CharacterRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use NickBeen\RickAndMortyPhpApi\Character;
use NickBeen\RickAndMortyPhpApi\Episode;
use NickBeen\RickAndMortyPhpApi\Dto\Episode as CharacterEpisode;

class CharacterRepository implements CharacterRepositoryInterface
{
    public function __construct(private Character $character, private Episode $episode)
    {
    }

    public function getAll(?int $page): array
    {
        $key = __CLASS__ . '::' . __FUNCTION__ . '::page::' . $page;

        try {
            $characters = Cache::rememberForever($key, fn () => $this->character->page($page)->get());

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
            $character = Cache::rememberForever($key, function () use ($id) {
                $character = $this->character->get($id);
                $character->episodes_extended = $this->getEpisodes($character->episode ?? []);

                return $character;
            });

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

    private function getEpisodes(array $episodes): array|CharacterEpisode
    {
        $results = [];

        foreach ($episodes as $episode) {
            $position = strrpos((string) $episode, '/');
            $id = substr($episode, $position + 1);

            if (is_numeric($id)) {
                $results[] = intval($id);
            };
        }

        if ($results) {
            $results = $this->episode->get(...$results);

            if (is_array($results)) {
                return $results;
            } elseif (is_object($results)) {
                return [$results];
            }
        }

        return $results;
    }
}
