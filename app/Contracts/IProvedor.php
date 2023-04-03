<?php

namespace App\Contracts;

use Illuminate\Http\Client\Response;

interface IProvedor
{
    public function requisicaoFalhou(Response $response): bool;

    public function processarRetorno(string $dados): array;
}
