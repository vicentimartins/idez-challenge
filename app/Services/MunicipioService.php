<?php

namespace App\Services;

use App\Contracts\IProvedor;
use App\Exceptions\ProvedorIndisponivelException;
use Generator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class MunicipioService
{
    public function __construct(private IProvedor $provedorDadosService)
    {
    }

    public function buscarMunicipioUf(string $uf): array
    {
        $response = Http::get(
            sprintf($this->definirProvedor(), strtoupper($uf))
        );

        if ($this->provedorDadosService->requisicaoFalhou($response)) {
            throw new ProvedorIndisponivelException(
                'Provedor dos dados indisponível no momento',
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        $resultado = $this->provedorDadosService->processarRetorno($response);

        Redis::set(
            sprintf('%s_UF_%s', env('PROVEDOR_DADOS'), $uf),
            json_encode($resultado)
        );

        return $resultado;
    }

    private function definirProvedor(): string
    {
        if ('ibge' === env('PROVEDOR_DADOS')) {
            return config('apiprovider.ibge');
        }

        return config('apiprovider.brasil');
    }
}
