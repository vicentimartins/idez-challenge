<?php

namespace App\Http\Controllers;

use App\Http\Requests\UFRequest;
use App\Services\MunicipioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;

class MunicipioController extends Controller
{
    public function __construct(
        private MunicipioService $municipioService
    ) {
    }

    public function __invoke(UFRequest $request): JsonResponse
    {

        $input = $request->safe();

        $municipiosCache = Redis::get(
            sprintf(
                '%s_UF_%s',
                env('REDIS_PREFIX'),
                $input->uf
            )
        );

        if (!empty($municipiosCache)) {
            return response()
                ->json(json_decode($municipiosCache, true));
        }

        $data = $this->municipioService->buscarMunicipioUf($input->uf);


        return response()
            ->json($data);
    }
}
