<?php

namespace App\Services;

use App\Contracts\IProvedor;
use Illuminate\Http\Client\Response;
use Generator;

class ProvedorIbgeService implements IProvedor
{
    public function requisicaoFalhou(Response $response): bool
    {
        return "[]" === $response->body();
    }

    public function processarRetorno(string $dados): array
    {
        $retorno = json_decode($dados, true);
        $resultado = [];

        foreach ($this->processarDadosIbge($retorno) as $municipio) {
            $resultado[] = $municipio;
        }

        return $resultado;
    }

    private function processarDadosIbge(array $retorno): Generator
    {
        $resultado = [];

        foreach ($retorno as $municipios) {
            array_walk($municipios, function ($value, $key) use (&$resultado) {
                if ('nome' === $key) {
                    $resultado['name'] = $value;
                };

                if ('id' === $key) {
                    $resultado['ibge_code'] = $value;
                };
            });

            krsort($resultado);

            yield $resultado;
        }
    }
}
