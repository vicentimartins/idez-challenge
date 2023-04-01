<?php

namespace App\Http\Controllers;

use App\Http\Requests\UFRequest;
use Illuminate\Http\JsonResponse;

class MunicipioController extends Controller
{
    public function __invoke(UFRequest $request): JsonResponse
    {
        return new JsonResponse();
    }
}
