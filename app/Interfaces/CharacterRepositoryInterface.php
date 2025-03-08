<?php

namespace App\Interfaces;

interface CharacterRepositoryInterface
{
    public function getAll(?int $page): array;
    public function getById(int $id): array;
}
