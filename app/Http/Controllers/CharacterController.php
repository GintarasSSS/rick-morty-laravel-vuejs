<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCharactersIndexRequest;
use App\Http\Requests\GetCharactersShowRequest;
use App\Repositories\CharacterRepository;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CharacterController extends Controller
{
    public function __construct(private readonly CharacterRepository $repository)
    {
    }

    public function index(GetCharactersIndexRequest $request): JsonResponse
    {
        return response()->json(
            $this->repository->getAll($request->validated('page', 1)),
            Response::HTTP_OK
        );
    }

    public function show(GetCharactersShowRequest $request): JsonResponse
    {
        return response()->json(
            $this->repository->getById($request->validated('id')),
            Response::HTTP_OK
        );
    }
}
