<?php

namespace App\Services;

use App\Exceptions\ProvedorIndisponivelException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;

class MunicipioService
{
    public function buscarMunicipioDaUf(string $uf): array
    {
        $response = Http::get(
            sprintf('https://brasilapi.com.br/api/ibge/municipios/v1/%s', strtoupper($uf))
        );

        if ($response->failed()) {
            throw new ProvedorIndisponivelException(
                'Provedor dos dados indisponível no momento',
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return json_decode($response->body(), true);
    }
}
