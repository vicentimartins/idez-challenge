<?php

namespace App\Http\Controllers;

use App\Http\Requests\UFRequest;
use App\Services\MunicipioService;
use Illuminate\Http\JsonResponse;

class MunicipioController extends Controller
{
    public function __construct(
        private MunicipioService $municipioService
    ) {
    }

    public function __invoke(UFRequest $request): JsonResponse
    {
        $input = $request->safe();
        $data = $this->municipioService->buscarMunicipioDaUf($input->uf);

        return response()
            ->json($data);
    }
}
