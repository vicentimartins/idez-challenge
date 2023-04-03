<?php

namespace App\Services;

use App\Contracts\IProvedor;
use Illuminate\Http\Client\Response;

class ProvedorBrasilApiService implements IProvedor
{
    public function requisicaoFalhou(Response $response): bool
    {
        return $response->failed();
    }

    public function processarRetorno(string $dados): array
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
}
