<?php

namespace App\Http\Controllers;

use App\Http\Requests\UFRequest;
use App\Http\Resources\MunicipioResource;
use App\Models\Municipio;
use App\Services\MunicipioService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;

class MunicipioController extends Controller
{
    public function __construct(
        private MunicipioService $municipioService
    ) {
    }

    public function __invoke(UFRequest $request)
    {
        $input = $request->all();

        $municipiosCache = Redis::get(
            $this->gerarChaveCache(
                $input['uf'],
                $input['page'] ?? null
            )
        );

        if (!empty($municipiosCache)) {
            return json_decode($municipiosCache, true);
        }

        $this->municipioService->buscarMunicipioUf($input['uf']);


        $municipio = new Municipio();
        $query =  $municipio->where(fn () => $municipio->ufs->where('name', $input['uf']))
            ->paginate(columns: ['name', 'ibge_code']);

        Redis::set($this->gerarChaveCache($input['uf'], $input['page']), $query->toJson());

        return MunicipioResource::collection($query);
    }

    private function gerarChaveCache(string $uf, ?int $page = null): string
    {
        if (!$page) {
            return sprintf('%s_UF_%s', env('PROVEDOR_DADOS'), $uf);
        }
        return sprintf('%s_UF_%s_page_%d', env('PROVEDOR_DADOS'), $uf, $page);
    }
}
