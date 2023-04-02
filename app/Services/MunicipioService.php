<?php

namespace App\Services;

use App\Exceptions\ProvedorIndisponivelException;
use Generator;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;

class MunicipioService
{
    public function buscarMunicipioUf(string $uf): array
    {
        $response = Http::get(
            sprintf($this->definirProvedor(), strtoupper($uf))
        );

        if ($this->requisicaoFalhou($response)) {
            throw new ProvedorIndisponivelException(
                'Provedor dos dados indisponível no momento',
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return $this->processarRetorno($response);
    }

    private function requisicaoFalhou(ClientResponse $response): bool
    {
        if ('ibge' === env('PROVEDOR_DADOS')) {
            return "[]" === $response->body();
        }

        return $response->failed();
    }

    private function definirProvedor(): string
    {
        if ('ibge' === env('PROVEDOR_DADOS')) {
            return config('apiprovider.ibge');
        }

        return config('apiprovider.brasil');
    }

    private function processarRetorno(string $retorno): array
    {
        if ('brasil api' === env('PROVEDOR_DADOS')) {
            return $this->processarDadosBrasilApi($retorno);
        }

        $retorno = json_decode($retorno, true);
        $resultado = [];

        foreach ($this->processarDadosIbge($retorno) as $key => $value) {
            $resultado[$key] = $value;
        }

        return $resultado;
    }

    private function processarDadosBrasilApi(string $dados): array
    {
        $dados = str_replace(
            sprintf('%s', 'nome'),
            sprintf('%s', 'name'),
            $dados
        );

        $dados = str_replace(
            sprintf('%s', 'codigo_ibge'),
            sprintf('%s', 'ibge_code'),
            $dados
        );

        return json_decode($dados, true);
    }

    private function processarDadosIbge(array $retorno): Generator
    {
        $resultado = [];

        foreach ($retorno as $municipios) {
            foreach ($municipios as $key => $value) {
                if ('nome' === $key) {
                    $resultado['name'] = $value;
                };

                if ('id' === $key) {
                    $resultado['ibge_code'] = $value;
                };

                continue;
            }

            krsort($resultado);

            yield $resultado;
        }
    }
}
